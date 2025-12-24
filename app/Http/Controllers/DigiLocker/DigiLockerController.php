<?php

namespace App\Http\Controllers\DigiLocker;

use App\Http\Controllers\BaseController as Controller;
use App\Models\DigiLocker\DigiLockerDocuments;
use App\Models\DigiLocker\DigiLockerRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class DigiLockerController extends Controller
{

    // Digilocker callback URL
    public function callback(Request $request)
    {
        try {
            $verification_id = $request->get('verification_id');

            $digilocker_request = DigiLockerRequest::where('verification_id', $verification_id)->first();

            if (!$digilocker_request) {
                Log::error('Digilocker Callback Error: Invalid verification_id: ' . $verification_id);
                return view('digilocker.index', [
                    'status' => 'error'
                ]);
            }

            if ($digilocker_request->csf_digilocker_status == 'pending' || $digilocker_request->csf_digilocker_status == 'created') {

                $digilocker_status_response = digilocker_verification_status(
                    $digilocker_request->csf_reference_id,
                    $digilocker_request->verification_id
                );

                if (!$digilocker_status_response['status']) {
                    Log::error('Digilocker Verify Account Error: ', $digilocker_status_response);
                    throw new \Exception($digilocker_status_response['message']);
                }
                Log::debug('Digilocker Status Response: ', $digilocker_status_response);

                $status = strtolower($digilocker_status_response['data']['status']);

                $digilocker_request->csf_digilocker_status = $status;
                $digilocker_request->csf_document_consent = $digilocker_status_response['data']['document_consent'] ?? null;
                $digilocker_request->csf_status_response_body = json_encode($digilocker_status_response['data']);
                $digilocker_request->save();

                if ($status == 'authenticated') {
                    Log::info('Digilocker Verification Completed: ', [
                        'verification_id' => $digilocker_request->verification_id,
                    ]);

                    digilocaker_fetch_documents($digilocker_request->verification_id);
                }
            }


            // show success UI
            return view('digilocker.index', [
                'status' => $digilocker_request->csf_digilocker_status
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

    //Initiate digilocker verification
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
            $digilocker_request->requester_id = 'N/A';
            $digilocker_request->verification_id = $request->identify_number . '_' . time();
            $digilocker_request->identify_number = $request->identify_number;
            $digilocker_request->documents_requested = json_encode($request->document_requested);
            $digilocker_request->request_body = json_encode($request->except('client'));
            $digilocker_request->save();

            $verify_response = csf_verify_account(
                $digilocker_request->verification_id,
                $digilocker_request->identify_number,
                $request->identify_type
            );

            if (!$verify_response['status']) {
                Log::error('Digilocker Verify Account Error: ', $verify_response);
                throw new \Exception($verify_response['message']);
            }
            Log::debug('Digilocker Verify Account Response: ', $verify_response);

            // create url flow
            $status = strtolower($verify_response['data']['status']);
            $csf_user_flow = $status === 'account_exists' ? 'signin' : 'signup';

            $create_url_response = csf_create_url(
                $digilocker_request->verification_id,
                $request->document_requested,
                route('digilocker.callback'), // Assuming you have a route named 'digilocker.callback'
                $csf_user_flow
            );

            if (!$create_url_response['status']) {
                Log::error('Digilocker Create URL Error: ', $create_url_response);
                throw new \Exception($create_url_response['message']);
            }
            Log::debug('Digilocker Create URL Response: ', $create_url_response);

            $digilocker_request->csf_reference_id = $create_url_response['data']['reference_id'];
            $digilocker_request->csf_digilocker_status = strtolower($create_url_response['data']['status']);
            $digilocker_request->save();

            DB::commit();

            Log::debug('Digilocker Initiate Response: ', $create_url_response);

            return response()->json([
                'status' => true,
                'data' => [
                    'verification_id' => $create_url_response['data']['verification_id'],
                    'digilocker_url' => $create_url_response['data']['url']
                ]
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

    //check digilocker status
    public function check_status($verification_id)
    {
        try {
            $digilocker_request = DigiLockerRequest::where('verification_id', $verification_id)->first();

            if (!$digilocker_request) {
                return response()->json([
                    'status' => false,
                    'message' => 'Invalid verification_id'
                ], 404);
            }

            if ($digilocker_request->csf_digilocker_status == 'pending' || $digilocker_request->csf_digilocker_status == 'created') {

                $digilocker_status_response = digilocker_verification_status(
                    $digilocker_request->csf_reference_id,
                    $digilocker_request->verification_id
                );

                if (!$digilocker_status_response['status']) {
                    Log::error('Digilocker Verify Account Error: ', $digilocker_status_response);
                    throw new \Exception($digilocker_status_response['message']);
                }
                Log::debug('Digilocker Status Response: ', $digilocker_status_response);

                $status = strtolower($digilocker_status_response['data']['status']);

                $digilocker_request->csf_digilocker_status = $status;
                $digilocker_request->csf_document_consent = $digilocker_status_response['data']['document_consent'] ?? null;
                $digilocker_request->csf_status_response_body = json_encode($digilocker_status_response['data']);
                $digilocker_request->save();

                if ($status == 'authenticated') {
                    Log::info('Digilocker Verification Completed: ', [
                        'verification_id' => $digilocker_request->verification_id,
                    ]);

                    digilocaker_fetch_documents($digilocker_request->verification_id);
                }
            }
            Log::debug('Digilocker Status Retrieved from DB: ', [
                'csf_digilocker_status' => $digilocker_request->csf_digilocker_status,
                'verification_id' => $digilocker_request->verification_id,
            ]);

            return response()->json([
                'status' => true,
                'data' => [
                    'verification_id' => $digilocker_request->verification_id,
                    'csf_digilocker_status' => $digilocker_request->csf_digilocker_status,
                    'csf_document_consent' => $digilocker_request->csf_document_consent,
                    'csf_status_response_body' => json_decode($digilocker_request->csf_status_response_body),
                ]
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'status' => false,
                'message' => 'Check status failed',
                'error' => $e->getMessage(),

            ], 500);
        }
    }

    //get document details
    public function fetch_document(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'verification_id'     => 'required|string',
                'document_name'     => 'required|string',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validation error',
                    'errors' => $validator->errors()
                ], 422);
            }

            $digilocker_request = DigiLockerRequest::where('verification_id', $request->verification_id)->first();
            if (!$digilocker_request) {
                return response()->json([
                    'status' => false,
                    'message' => 'Invalid verification_id'
                ], 404);
            }

            $fetch_document = DigiLockerDocuments::where('verification_id', $digilocker_request->id)
                ->where('document_name', $request->document_name)
                ->first();

            if (!$fetch_document) {
                return response()->json([
                    'status' => false,
                    'message' => 'document not found'
                ], 404);
            }

            return response()->json([
                'status' => true,
                'message' => 'Document fetched successfully',
                'data' => [
                    'document_name' => $fetch_document->document_name,
                    'response_body' => json_decode($fetch_document->response_body),
                ]
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'status' => false,
                'message' => 'Document fetch failed',
                'error' => $e->getMessage(),

            ], 500);
        }
    }
}
