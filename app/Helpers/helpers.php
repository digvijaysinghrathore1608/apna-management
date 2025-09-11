<?php

use Illuminate\Support\Facades\File;

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
