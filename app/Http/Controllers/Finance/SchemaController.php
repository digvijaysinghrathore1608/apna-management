<?php

namespace App\Http\Controllers\Finance;

use App\Http\Controllers\BaseController as Controller;
use App\Http\Requests\Finance\SchemaCreateRequest;
use App\Http\Requests\Finance\SchemaUpdateRequest;
use App\Repositories\Interface\Finance\LoanSchemaRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SchemaController extends Controller
{
    public function __construct(
        private readonly LoanSchemaRepositoryInterface $loanSchemaRepo,
    ) {}
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        return $this->handleRequest(function () use ($request) {
            $result = $this->loanSchemaRepo->getDataTable($request);
            if ($result) {
                return $result;
            }
            return view('finance.schema.index');
        });
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return $this->handleRequest(function () {
            $fields = [
                ['name' => 'name', 'label' => 'name', 'placeholder' => 'Enter Schema Name', 'required' => true],
                ['name' => 'amount', 'label' => 'amount', 'placeholder' => 'Enter Amount', 'required' => true, 'type' => 'number'],
                ['name' => 'interest_rate', 'label' => 'loan_interest_rate', 'placeholder' => 'Enter Interest rate', 'required' => true, 'type' => 'number'],
                ['name' => 'insurance_amount', 'label' => 'loan_insurance_amount', 'placeholder' => 'Enter Insurance amount', 'required' => true, 'type' => 'number'],
                ['name' => 'duration_type', 'label' => 'duration_type', 'placeholder' => 'Duration Type', 'type' => 'select', 'options' => duration_type(), 'required' => true],
                ['name' => 'duration', 'label' => 'duration', 'placeholder' => 'Enter duration', 'required' => true, 'type' => 'number'],
                ['name' => 'late_fee', 'label' => 'late_fee', 'placeholder' => 'Enter late fee', 'required' => true, 'type' => 'number'],
                ['name' => 'late_fee_apply', 'label' => 'late_fee_apply', 'placeholder' => 'late fee apply', 'type' => 'select', 'options' => days(), 'required' => true],
                ['name' => 'emi_amount', 'label' => 'emi_amount', 'placeholder' => 'emi amount', 'required' => true, 'type' => 'number'],
            ];

            return view('finance.schema.create', compact('fields'));
        });
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(SchemaCreateRequest $request)
    {
        return $this->handleRequest(function () use ($request) {
            $validated = $request->validated();
            $validated['added_by'] = Auth::id();

            $this->loanSchemaRepo->add($validated);
            return redirect()->route('microfinance.schema.index');
        }, successMessage: 'schema_created_successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        return $this->handleRequest(function () use ($id) {
            $schema = $this->loanSchemaRepo->getFirstWhere(params: ['id' => $id]);
            $fields = [
                ['name' => 'name', 'label' => 'name', 'placeholder' => 'Enter Schema Name', 'required' => true, 'value' => $schema->name],
                ['name' => 'amount', 'label' => 'amount', 'placeholder' => 'Enter Amount', 'required' => true, 'type' => 'number', 'value' => $schema->amount],
                ['name' => 'interest_rate', 'label' => 'loan_interest_rate', 'placeholder' => 'Enter Interest rate', 'required' => true, 'type' => 'number', 'value' => $schema->interest_rate],
                ['name' => 'insurance_amount', 'label' => 'loan_insurance_amount', 'placeholder' => 'Enter Insurance amount', 'required' => true, 'type' => 'number', 'value' => $schema->insurance_amount],
                ['name' => 'duration_type', 'label' => 'duration_type', 'placeholder' => 'Duration Type', 'type' => 'select', 'options' => duration_type(), 'required' => true, 'value' => $schema->duration_type],
                ['name' => 'duration', 'label' => 'duration', 'placeholder' => 'Enter duration', 'required' => true, 'type' => 'number', 'value' => $schema->duration],
                ['name' => 'late_fee', 'label' => 'late_fee', 'placeholder' => 'Enter late fee', 'required' => true, 'type' => 'number', 'value' => $schema->late_fee],
                ['name' => 'late_fee_apply', 'label' => 'late_fee_apply', 'placeholder' => 'late fee apply', 'type' => 'select', 'options' => days(), 'required' => true, 'value' => $schema->late_fee_apply],
                ['name' => 'emi_amount', 'label' => 'emi_amount', 'placeholder' => 'emi amount', 'required' => true, 'type' => 'number', 'value' => $schema->emi_amount],
            ];

            return view('finance.schema.edit', compact('fields', 'schema'));
        });
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(SchemaUpdateRequest $request, string $id)
    {
        return $this->handleRequest(function () use ($request, $id) {
            $validated = $request->validated();

            $this->loanSchemaRepo->update(id: $id, data: $validated);
            return redirect()->route('microfinance.schema.index');
        }, successMessage: 'schema_update_successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        return $this->handleRequest(function () use ($id) {
            $this->loanSchemaRepo->delete(['id' => $id]);
            return redirect()->route('microfinance.schema.index');
        }, successMessage: 'schema_deleted_successfully');
    }
}
