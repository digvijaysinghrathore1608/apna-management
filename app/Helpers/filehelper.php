<?php

use Illuminate\Support\Facades\Storage;

if (!function_exists('file_upload')) {
    function file_upload($file, $path)
    {
        $disk = config('filesystems.default');
        return $file->store($path, $disk);
    }
}

if (!function_exists('file_get_url')) {
    function file_get_url($path)
    {
        if (!$path) return null;

        $disk = config('filesystems.default');

        if ($disk === 's3') {
            return Storage::temporaryUrl($path, now()->addMinutes(10));
        }

        return Storage::url($path);
    }
}

if (!function_exists('file_delete')) {
    function file_delete($path)
    {
        if (!$path) return;
        $disk = config('filesystems.default');
        Storage::disk($disk)->delete(paths: $path);
    }
}
