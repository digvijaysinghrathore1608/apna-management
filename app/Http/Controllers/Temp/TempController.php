<?php

namespace App\Http\Controllers\Temp;

use App\Http\Controllers\Controller;
use App\Models\Temp\ApnaSiteTemp;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TempController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $temp_site_data = ApnaSiteTemp::all();
        return response()->json(['data' => $temp_site_data]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'employee_id' => 'required|string',
            'data' => 'required|array'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors()
            ], 422);
        }

        $exist_employee = ApnaSiteTemp::where('employee_id', $request->employee_id)->exists();
        if ($exist_employee) {
            return response()->json([
                'status' => 'error',
                'message' => 'employee id already exist'
            ], 409);
        }

        $employee = ApnaSiteTemp::create([
            'employee_id' => $request->employee_id,
            'content' => json_encode($request->data)
        ]);

        return response()->json([
            'status' => 'success',
            'data' => $employee
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $employee = ApnaSiteTemp::where('employee_id', $id)->first();
        if (!$employee) {
            return response()->json([
                'status' => 'error',
                'message' => 'employee id not exist'
            ], 404);
        }
        return response()->json($employee);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        // Step 1: Find employee
        $employee = ApnaSiteTemp::where('employee_id', $id)->first();
        if (!$employee) {
            return response()->json([
                'status' => 'error',
                'message' => 'Employee not found'
            ], 404);
        }

        // Step 2: Validate request
        $validator = Validator::make($request->all(), [
            'data' => 'required|array'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors()
            ], 422);
        }

        // Step 5: Update employee
        $employee->update([
            'content' => json_encode($request->data)
        ]);

        $employee->refresh();

        return response()->json([
            'status' => 'success',
            'data' => $employee
        ], 200);
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        // Step 1: Find employee
        $employee = ApnaSiteTemp::where('employee_id', $id)->first();

        if (!$employee) {
            return response()->json([
                'status' => 'error',
                'message' => 'Employee not found'
            ], 404);
        }

        // Step 2: Delete employee
        $employee->delete();

        // Step 3: Return success response
        return response()->json([
            'status' => 'success',
            'message' => 'Employee deleted successfully'
        ], 200);
    }
}
