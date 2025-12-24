<?php

namespace App\Http\Controllers;

use App\Models\ClientCredential;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class ClientCredentialController extends Controller
{
    /**
     * Get all client credentials
     */
    public function index()
    {
        $data = ClientCredential::with('service')->latest()->get();

        return response()->json([
            'status' => true,
            'data' => $data
        ]);
    }

    /**
     * Get single client credential
     */
    public function show($id)
    {
        $client = ClientCredential::with('service')->find($id);

        if (!$client) {
            return response()->json([
                'status' => false,
                'message' => 'Client not found'
            ], 404);
        }

        return response()->json([
            'status' => true,
            'data' => $client
        ]);
    }

    /**
     * Create new client credential
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => [
                'required',
                'string',
                Rule::unique('client_credentials')
                    ->where(function ($query) use ($request) {
                        return $query
                            ->where('service_id', $request->service_id)
                            ->whereNull('deleted_at');
                    }),
            ],
            'mobile'     => 'nullable|string',
            'email'      => 'nullable|email',
            'service_id' => 'required|exists:services,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        $plainSecret = Str::random(40);

        $client = ClientCredential::create([
            'name'       => $request->name,
            'mobile'     => $request->mobile,
            'email'      => $request->email,
            'service_id' => $request->service_id,
            'client_id'  => 'cli_' . Str::random(16),
            'secret_key' => hash('sha256', $plainSecret),
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Client credential created successfully',
            'data' => [
                'client' => $client,
                'plain_secret' => $plainSecret // ⚠️ only once
            ]
        ], 201);
    }

    /**
     * Update client credential
     */
    public function update(Request $request, $id)
    {
        $client = ClientCredential::find($id);

        if (!$client) {
            return response()->json([
                'status' => false,
                'message' => 'Client not found'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'name'       => 'sometimes|string',
            'mobile'     => 'nullable|string',
            'email'      => 'nullable|email',
            'service_id' => 'sometimes|exists:services,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        $client->update($request->only([
            'name',
            'mobile',
            'email',
            'service_id'
        ]));

        return response()->json([
            'status' => true,
            'message' => 'Client updated successfully',
            'data' => $client
        ]);
    }

    /**
     * Delete client credential (Soft delete)
     */
    public function destroy($id)
    {
        $client = ClientCredential::find($id);

        if (!$client) {
            return response()->json([
                'status' => false,
                'message' => 'Client not found'
            ], 404);
        }

        $client->delete();

        return response()->json([
            'status' => true,
            'message' => 'Client deleted successfully'
        ]);
    }
}
