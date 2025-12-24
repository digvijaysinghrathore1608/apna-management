<?php

use App\Models\DigiLocker\DigiLockerDocuments;
use App\Models\DigiLocker\DigiLockerRequest;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

if (!function_exists('get_cashfree_public_key')) {

    function get_cashfree_public_key()
    {
        $key = env('CASHFREE_PUBLIC_KEY');

        if (!$key) {
            throw new \Exception('Public key not found in ENV');
        }

        // \n ko actual newline me convert
        $key = str_replace('\n', PHP_EOL, $key);

        return openssl_pkey_get_public($key);
    }
}


if (!function_exists('get_cashfree_signature')) {

    function get_cashfree_signature()
    {
        $clientId = config('services.cashfree.client_id');

        $publicKey = get_cashfree_public_key();

        if (!$publicKey) {
            throw new \Exception('Invalid public key');
        }

        $encodedData = $clientId . "." . time();

        return encrypt_RSA($encodedData, $publicKey);
    }
}

if (!function_exists('encrypt_RSA')) {

    function encrypt_RSA($plainData, $publicKey)
    {
        if (openssl_public_encrypt(
            $plainData,
            $encrypted,
            $publicKey,
            OPENSSL_PKCS1_OAEP_PADDING
        )) {
            return base64_encode($encrypted);
        }

        return null;
    }
}

if (!function_exists('digilocker_verification_status')) {
    function digilocker_verification_status(string $referenceId, string $verificationId)
    {
        $response = Http::withHeaders([
            'x-client-id'     => config('services.cashfree.client_id'),
            'x-client-secret' => config('services.cashfree.client_secret'),
            'x-cf-signature'     => get_cashfree_signature(),
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
}

if (!function_exists('digilocaker_fetch_documents')) {
    function digilocaker_fetch_documents($verificationId)
    {
        try {
            $digilocker_request = DigiLockerRequest::where('verification_id', $verificationId)->first();

            if (!$digilocker_request) {
                Log::warning('DigiLocker Document Request not found for verification_id: ' . $verificationId);
                return;
            }

            $csf_document_consents = json_decode(
                $digilocker_request->csf_document_consent,
                true
            );

            if (empty($csf_document_consents) || !is_array($csf_document_consents)) {
                Log::warning('No document consent found', [
                    'verification_id' => $verificationId
                ]);
                return;
            }

            foreach ($csf_document_consents as  $document_name) {
                $document_exist = DigiLockerDocuments::where('verification_id', $digilocker_request->id)
                    ->where('document_name', $document_name)
                    ->exists();

                if ($document_exist) {
                    Log::error('Document already fetched: ' . $document_name, [
                        'verification_id' => $verificationId,
                    ]);
                    continue;
                }

                $fetch_docs_response = csf_digilocker_get_documents(
                    $document_name,
                    $digilocker_request->verification_id,
                    $digilocker_request->csf_reference_id
                );

                if (!$fetch_docs_response['status']) {
                    Log::error('Digilocker document fetch Error: ', $fetch_docs_response);
                    throw new \Exception($fetch_docs_response['message']);
                }

                DigiLockerDocuments::create([
                    'verification_id' => $digilocker_request->id,
                    'document_name'   => strtolower($document_name),
                    'response_body'   => json_encode($fetch_docs_response['data']),
                ]);
            }

            return;
        } catch (\Throwable $e) {
            Log::error('Error fetching DigiLocker Document Request: ' . $e->getMessage(), [
                'verification_id' => $verificationId,
            ]);
            return;
        }
    }
}


//csf document fetch api
if (!function_exists('csf_digilocker_get_documents')) {
    function csf_digilocker_get_documents(string $document_type, string $verification_id, string $reference_id)
    {
        $response = Http::withHeaders([
            'x-client-id'     => config('services.cashfree.client_id'),
            'x-client-secret' => config('services.cashfree.client_secret'),
            'x-cf-signature'     => get_cashfree_signature(),
        ])->get(
            config('services.cashfree.base_url') . '/verification/digilocker/document/' . $document_type,
            [
                'verification_id' => $verification_id,
                'reference_id' => $reference_id,
            ]
        );

        if ($response->failed()) {
            return [
                'status'  => false,
                'message' => 'Cashfree Document Fetch API failed',
                'error'   => $response->json(),
            ];
        }

        return [
            'status' => true,
            'data'   => $response->json(),
        ];
    }
}

//csf verify account api
if (!function_exists('csf_verify_account')) {
    function csf_verify_account($verification_id, $identify_number, $identify_type): array
    {
        $response = Http::withHeaders([
            'Content-Type'    => 'application/json',
            'x-client-id'     => config('services.cashfree.client_id'),
            'x-client-secret' => config('services.cashfree.client_secret'),
            'x-cf-signature'     => get_cashfree_signature(),
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
}

//csf create digilocker url api
if (!function_exists('csf_create_url')) {
    function csf_create_url($verification_id, $document_requested, $redirect_url, $user_flow)
    {
        $response = Http::withHeaders([
            'Content-Type'     => 'application/json',
            'x-client-id'      => config('services.cashfree.client_id'),
            'x-client-secret' => config('services.cashfree.client_secret'),
            'x-cf-signature'     => get_cashfree_signature(),
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
