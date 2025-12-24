<?php

use App\Models\BusinessSetting;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Http;

if (!function_exists('assetOrDefault')) {
    /**
     * Generate asset URL, fallback to default if not exist
     *
     * @param string|null $path
     * @param string $default
     * @return string
     */

    function assetOrDefault(?string $path, string $default = 'assets/img/placeholder.svg'): string
    {
        if ($path && File::exists(public_path($path))) {
            return asset($path);
        }
        return asset($default);
    }
}


if (!function_exists('translate')) {
    /**
     * Generate asset URL, fallback to default if not exist
     *
     * @param string $key
     * @param array $replace
     * @param string|null $locale
     * @return string
     */
    function translate($key, $replace = [], $locale = null): string
    {
        // Laravel translation attempt
        $translation = __($key, $replace, $locale);

        // Agar translation nahi mili (same string wapas aayi)
        if ($translation === $key) {
            // underscore ko space me convert karke ucwords lagao
            return ucwords(str_replace('_', ' ', $key));
        }

        return ucwords($translation);
    }
}

if (!function_exists('genders')) {

    function genders(): array
    {
        return GENDERS;
    }
}

if (!function_exists('duration_type')) {

    function duration_type(): array
    {
        return DURATION_TYPE;
    }
}

if (!function_exists('work_type')) {

    function work_type(): array
    {
        return WORK_TYPE;
    }
}

if (!function_exists('days')) {

    function days(): array
    {
        return range(1, 31);;
    }
}

if (!function_exists('extractNumberPart')) {
    /**
     * Extract digits from start, end or both sides of a number.
     *
     * @param string|int $number   The number
     * @param int $firstCount      How many digits from the start
     * @param int $lastCount       How many digits from the end
     * @return string|null
     */
    function extractNumberPart($number, int $firstCount = 0, int $lastCount = 0): ?string
    {
        $number = (string) $number;
        $length = strlen($number);

        // check valid length
        if ($length < max($firstCount, $lastCount)) {
            return "Invalid number";
        }

        $result = '';

        if ($firstCount > 0) {
            $result .= substr($number, 0, $firstCount);
        }

        if ($lastCount > 0) {
            if ($result !== '') {
                $result .= '...'; // separator
            }
            $result .= substr($number, -$lastCount);
        }

        return $result ?: null;
    }
}


if (! function_exists('canAny')) {
    function canAny(array $abilities): bool
    {
        foreach ($abilities as $ability) {
            if (Gate::allows($ability)) {
                return true;
            }
        }
        return false;
    }
}

if (! function_exists('min_dob')) {
    function min_dob($min_age = MINIMUM_AGE)
    {
        return now()->subYears($min_age)->format('Y-m-d');
    }
}

if (!function_exists('business_setting_all')) {
    function business_setting_all()
    {
        return Cache::remember('business_setting_all', 60, function () {
            return BusinessSetting::all();
        });
    }
}

if (!function_exists('business_setting_by_key')) {
    function business_setting_by_key($key, $default = null)
    {
        return business_setting_all()
            ->keyBy('key')
            ->get($key)
            ->value ?? $default;
    }
}


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

if (!function_exists('digilocker_document_fetch')) {
    function digilocker_document_fetch(string $document_type, string $verification_id, string $reference_id)
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
