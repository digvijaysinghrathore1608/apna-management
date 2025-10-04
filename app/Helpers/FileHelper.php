<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Storage;

class FileHelper
{
    public static function upload($file, $path)
    {
        $disk = config('filesystems.default');
        return $file->store($path, $disk);
    }

    public static function getUrl($path)
    {
        if (!$path) return null;

        $disk = config('filesystems.default');

        if ($disk === 's3') {
            return Storage::temporaryUrl($path, now()->addMinutes(10));
        }

        return Storage::url($path);
    }

    public static function delete($path)
    {
        if (!$path) return;
        $disk = config('filesystems.default');
        Storage::disk($disk)->delete(paths: $path);
    }
}
