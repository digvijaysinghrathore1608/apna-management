<?php

namespace App\Http\Controllers\Services;

use App\Http\Controllers\BaseController as Controller;
use App\Models\DigiLocker\DigiLockerRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class DigiLockerController extends Controller
{

    public function callback(Request $request)
    {
        try {
            $referenceId = $request->get('reference_id');

            $digilocker_request = DigiLockerRequest::where('verification_id', $referenceId)->first();

            if (!$digilocker_request) {
                Log::error('Digilocker Callback Error: Invalid verification_id: ' . $referenceId);
                return view('digilocker.index', [
                    'status' => 'error'
                ]);
            }

            $digilocker_status_response = $this->verification_status(
                $digilocker_request->csf_reference_id,
                $digilocker_request->verification_id
            );

            if (!$digilocker_status_response['status']) {
                Log::error('Digilocker Verify Account Error: ', $digilocker_status_response);
                throw new \Exception($digilocker_status_response['message']);
            }
            Log::success('Digilocker Status Response: ', $digilocker_status_response);

            $status = strtolower($digilocker_status_response['data']['status']);

            // show success UI
            return view('digilocker.index', [
                'status' => $status
            ]);
        } catch (\Throwable $e) {
            DB::rollBack();

            return response()->json([
                'status' => false,
                'message' => 'Digilocker initiation failed',
                'error' => $e->getMessage(),

            ], 500);
        }
    }

    public function initiate(Request $request)
    {
        DB::beginTransaction();
        try {
            $validator = Validator::make($request->all(), [
                'identify_number'     => 'required|string',
                'identify_type'     => 'required|string|in:aadhar,mobile',
                'document_requested'      => 'required|array',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validation error',
                    'errors' => $validator->errors()
                ], 422);
            }

            $digilocker_request = new DigiLockerRequest();
            $digilocker_request->requester_id = $request->client->id;
            $digilocker_request->verification_id = $request->client->id . '_' . $request->identify_number . '_' . time();
            $digilocker_request->identify_number = $request->identify_number;
            $digilocker_request->documents_requested = json_encode($request->document_requested);
            $digilocker_request->request_body = json_encode($request->except('client'));
            $digilocker_request->save();

            $verify_response = $this->verify_account(
                $digilocker_request->verification_id,
                $digilocker_request->identify_number,
                $request->identify_type
            );

            if (!$verify_response['status']) {
                Log::error('Digilocker Verify Account Error: ', $verify_response);
                throw new \Exception($verify_response['message']);
            }
            Log::success('Digilocker Verify Account Response: ', $verify_response);

            // create url flow
            $status = strtolower($verify_response['data']['status']);
            $csf_user_flow = $status === 'account_exists' ? 'signin' : 'signup';

            $create_url_response = $this->create_url(
                $digilocker_request->verification_id,
                $request->document_requested,
                route('digilocker.callback'), // Assuming you have a route named 'digilocker.callback'
                $csf_user_flow
            );

            if (!$create_url_response['status']) {
                Log::error('Digilocker Create URL Error: ', $create_url_response);
                throw new \Exception($create_url_response['message']);
            }
            Log::success('Digilocker Create URL Response: ', $create_url_response);

            $digilocker_request->csf_reference_id = $create_url_response['data']['reference_id'];
            $digilocker_request->csf_digilocker_status = strtolower($create_url_response['data']['status']);
            $digilocker_request->save();

            DB::commit();

            Log::success('Digilocker Initiate Response: ', $create_url_response);

            return response()->json([
                'status' => true,
                'data' => $create_url_response
            ]);
        } catch (\Throwable $e) {
            DB::rollBack();

            return response()->json([
                'status' => false,
                'message' => 'Digilocker initiation failed',
                'error' => $e->getMessage(),

            ], 500);
        }
    }

    private function verification_status(string $referenceId, string $verificationId)
    {
        $response = Http::withHeaders([
            'x-client-id'     => config('services.cashfree.client_id'),
            'x-client-secret' => config('services.cashfree.client_secret'),
        ])->get(
            config('services.cashfree.base_url') . '/verification/digilocker',
            [
                'reference_id'    => $referenceId,
                'verification_id' => $verificationId,
            ]
        );

        if ($response->failed()) {
            return [
                'status'  => false,
                'message' => 'Cashfree Verification API failed',
                'error'   => $response->json(),
            ];
        }

        return [
            'status' => true,
            'data'   => $response->json(),
        ];
    }


    // verify account with Cashfree DigiLocker API
    private function verify_account($verification_id, $identify_number, $identify_type)
    {
        $response = Http::withHeaders([
            'Content-Type'    => 'application/json',
            'x-client-id'     => config('services.cashfree.client_id'),
            'x-client-secret' => config('services.cashfree.client_secret'),
        ])->post(
            config('services.cashfree.base_url') . '/verification/digilocker/verify-account',
            [
                'verification_id' => $verification_id,
                $identify_type == 'aadhar' ? 'aadhaar_number' : 'mobile_number' => $identify_number,
            ]
        );

        if ($response->failed()) {
            return [
                'status'  => false,
                'message' => 'Cashfree Verify Account API failed',
                'error'   => $response->json(),
            ];
        }

        return [
            'status' => true,
            'data'   => $response->json(),
        ];
    }

    private function create_url($verification_id, $document_requested, $redirect_url, $user_flow)
    {
        $response = Http::withHeaders([
            'Content-Type'     => 'application/json',
            'x-client-id'      => config('services.cashfree.client_id'),
            'x-client-secret' => config('services.cashfree.client_secret'),
        ])->post(
            config('services.cashfree.base_url') . '/verification/digilocker',
            [
                'verification_id'    => $verification_id,
                'document_requested' => $document_requested,
                'redirect_url'       => $redirect_url,
                'user_flow'          => $user_flow,
            ]
        );

        if ($response->failed()) {
            return [
                'status'  => false,
                'message' => 'Cashfree Create URL API failed',
                'error'   => $response->json(),
            ];
        }
        return [
            'status' => true,
            'data'   => $response->json(),
        ];
    }
}
