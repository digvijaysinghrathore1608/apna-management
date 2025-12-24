<?php

namespace App\Http\Middleware;

use App\Models\ClientCredential;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ClientServiceMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, String $service): Response
    {
        $clientId = $request->header('X-Client-Id');
        $secret   = $request->header('X-Client-Secret');

        if (!$clientId || !$secret) {
            return response()->json([
                'message' => 'Client credentials missing'
            ], 401);
        }

        $client = ClientCredential::with('service')
            ->where('client_id', $clientId)
            ->where('secret_key', $secret)
            ->first();

        if (!$client) {
            return response()->json([
                'message' => 'Invalid client credentials'
            ], 401);
        }

        // 🔐 Service check
        if (
            !$client->service ||
            $client->service->name !== $service
        ) {
            return response()->json([
                'message' => 'Service not allowed for this client'
            ], 403);
        }

        $request->merge(['client' => $client]);

        return $next($request);
    }
}
