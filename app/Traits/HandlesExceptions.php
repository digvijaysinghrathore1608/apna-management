<?php

namespace App\Traits;

use Closure;
use Devrabiul\ToastMagic\Facades\ToastMagic;
use Exception;
use Illuminate\Support\Facades\Log;

trait HandlesExceptions
{
    public function handleRequest(Closure $callback, string $successMessage = null, string $redirectRoute = null)
    {
        try {
            $result = $callback();

            if ($successMessage) {
                ToastMagic::success(translate($successMessage));
            }

            return $result;
        } catch (Exception $e) {
            Log::error($e->getMessage(), ['trace' => $e->getTrace()]);
            ToastMagic::error(translate('something_went_wrong'));
            return back()->withInput();
        }
    }
}
