<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Gate;

class CanAny
{
    public function handle($request, Closure $next, ...$abilities)
    {
        $abilities = collect($abilities)
            ->flatMap(fn($a) => explode(',', $a))
            ->map(fn($a) => trim($a))
            ->filter()
            ->all();
        foreach ($abilities as $ability) {
            if (Gate::allows($ability)) {
                return $next($request);
            }
        }

        abort(403, 'Unauthorized');
    }
}
