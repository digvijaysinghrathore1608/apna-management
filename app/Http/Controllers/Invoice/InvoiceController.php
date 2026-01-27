<?php

namespace App\Http\Controllers\Invoice;

use App\Http\Controllers\BaseController as Controller;
use App\Models\Invoice\Invoice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class InvoiceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Invoice::with('template')
            ->latest()
            ->paginate(10);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        DB::beginTransaction();
        try {
            $validator = Validator::make($request->all(), [
                'template_id' => 'required|exists:invoice_template,id',
                'invoice_number' => 'required|unique:invoice,invoice_number',
                'customer_name' => 'required|string|max:255',
                'invoice_file' => 'required|file|mimes:pdf',
                'invoice_date' => 'required|date',
            ]);
            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validation error',
                    'errors' => $validator->errors()
                ], 422);
            }

            $path = file_upload($request->file('invoice_file'), 'invoices');

            Invoice::create([
                'template_id' => $request->template_id,
                'invoice_number' => $request->invoice_number,
                'customer_name' => $request->customer_name,
                'invoice_date' => $request->invoice_date,
                'invoice_path' => $path,
                'status' => 'generated',
            ]);

            DB::commit();
            return response()->json([
                'status' => true,
                'message' => 'Invoice created',
            ], 201);
        } catch (\Throwable $e) {
            DB::rollBack();
            return response()->json([
                'status' => false,
                'message' => 'Invoice creation failed',
                'error' => $e->getMessage(),

            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $invoice_number)
    {
        $invoice = Invoice::where('invoice_number', $invoice_number)->first();
        if (!$invoice) {
            return response()->json([
                'status' => false,
                'message' => 'Invoice not found',
            ], 404);
        }

        $invoice_url = file_get_url($invoice->invoice_path);

        return response()->json([
            'status' => true,
            'invoice_url' => $invoice_url,
            'disk' => config('filesystems.default'),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $invoice_number)
    {
        $invoice = Invoice::where('invoice_number', $invoice_number)->first();
        if (!$invoice) {
            return response()->json([
                'status' => false,
                'message' => 'Invoice not found',
            ], 404);
        }
        DB::beginTransaction();
        try {
            $validator = Validator::make($request->all(), [
                'status' => 'required|in:generated,checked',
            ]);
            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validation error',
                    'errors' => $validator->errors()
                ], 422);
            }

            $invoice->update([
                'status' => $request->status,
            ]);

            DB::commit();
            return response()->json([
                'status' => true,
                'message' => 'Invoice status updated',
            ], 201);
        } catch (\Throwable $e) {
            DB::rollBack();
            return response()->json([
                'status' => false,
                'message' => 'Invoice status update failed',
                'error' => $e->getMessage(),

            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
