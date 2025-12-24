<?php

namespace App\Jobs\DigiLocker;

use App\Models\DigiLocker\DigiLockerDocuments;
use App\Models\DigiLocker\DigiLockerRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class DocumentFetchJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;


    public string $verificationId;
    /**
     * Create a new job instance.
     */
    public function __construct(string $verificationId)
    {
        $this->verificationId = $verificationId;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {
            $digilocker_request = DigiLockerRequest::where('verification_id', $this->verificationId)->first();

            if (!$digilocker_request) {
                Log::warning('DigiLocker Document Request not found for verification_id: ' . $this->verificationId);
                return;
            }

            $csf_document_consents = json_decode(
                $digilocker_request->csf_document_consent,
                true
            );

            if (empty($csf_document_consents) || !is_array($csf_document_consents)) {
                Log::warning('No document consent found', [
                    'verification_id' => $this->verificationId
                ]);
                return;
            }

            foreach ($csf_document_consents as  $document_name) {
                $document_exist = DigiLockerDocuments::where('verification_id', $digilocker_request->id)
                    ->where('document_name', $document_name)
                    ->exists();

                if ($document_exist) {
                    Log::error('Document already fetched: ' . $document_name, [
                        'verification_id' => $this->verificationId,
                    ]);
                    continue;
                }

                $fetch_docs_response = digilocker_document_fetch(
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
                    'document_name'   => $document_name,
                    'response_body'   => json_encode($fetch_docs_response['data']),
                ]);
            }

            return;
        } catch (\Throwable $e) {
            Log::error('Error fetching DigiLocker Document Request: ' . $e->getMessage(), [
                'verification_id' => $this->verificationId,
            ]);
        }
    }
}
