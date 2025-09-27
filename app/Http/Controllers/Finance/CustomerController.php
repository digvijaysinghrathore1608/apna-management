<?php

namespace App\Http\Controllers\Finance;

use App\Http\Controllers\BaseController;
use App\Http\Requests\Finance\CustomerCreateRequest;
use App\Http\Requests\Finance\CustomerUpdateRequest;
use App\Repositories\Interface\Finance\BranchRepositoryInterface;
use App\Repositories\Interface\Finance\CustomerRepositoryInterface;
use App\Repositories\Interface\Finance\DocumentsIdRepositoryInterface;
use Devrabiul\ToastMagic\Facades\ToastMagic;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;

class CustomerController extends BaseController
{
    public function __construct(
        private readonly CustomerRepositoryInterface $customerRepo,
        private readonly BranchRepositoryInterface $branchRepo,
        private readonly DocumentsIdRepositoryInterface $documentsIdRepo,
    ) {}

    protected function saveDocument(string $name, string $number, int $relationId): void
    {
        $data = [
            'name' => $name,
            'number' => $number,
            'relation_table_name' => 'finance_customers',
            'relation_id' => $relationId
        ];
        $this->documentsIdRepo->add($data);
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        return $this->handleRequest(function () use ($request) {
            $result = $this->customerRepo->getDataTable($request);
            if ($result) {
                return $result;
            }
            return view('finance.customer.index');
        });
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return $this->handleRequest(function () {
            $branchs = $this->branchRepo->getList(dataLimit: 'all')->pluck('name', 'id')->toArray();

            $fields = [
                // Aadhaar & PAN
                ['name' => 'aadhaar_number', 'label' => 'Aadhaar Number', 'placeholder' => 'Enter aadhaar number', 'required' => true],
                ['name' => 'pan_number', 'label' => 'PAN Number', 'placeholder' => 'Enter PAN number', 'required' => true],

                // Names
                ['name' => 'f_name', 'label' => 'First Name', 'placeholder' => 'Enter first name', 'required' => true, 'col' => 4],
                ['name' => 'm_name', 'label' => 'Middle Name', 'placeholder' => 'Enter middle name', 'col' => 4],
                ['name' => 'l_name', 'label' => 'Last Name', 'placeholder' => 'Enter last name', 'required' => true, 'col' => 4],

                // Contact
                ['name' => 'mobile', 'label' => 'Mobile Number', 'placeholder' => 'Enter mobile number', 'type' => 'tel', 'required' => true, 'col' => 4],
                ['name' => 'email', 'label' => 'Email', 'placeholder' => 'Enter email', 'type' => 'email', 'col' => 4],

                // DOB + Gender + Branch
                ['name' => 'DOB', 'label' => 'Date of Birth', 'type' => 'date', 'required' => true, 'col' => 4],
                ['name' => 'gender', 'label' => 'Gender', 'type' => 'select', 'options' => genders(), 'required' => true, 'col' => 4],
                ['name' => 'branch_id', 'label' => 'Branch', 'type' => 'select', 'options' => $branchs, 'required' => true, 'col' => 4],

                // Parents
                ['name' => 'father_name', 'label' => 'Father Name', 'placeholder' => 'Enter father name', 'required' => true, 'col' => 4],
                ['name' => 'mother_name', 'label' => 'Mother Name', 'placeholder' => 'Enter mother name', 'required' => true, 'col' => 4],
                ['name' => 'in_law_father_name', 'label' => 'Father in Law Name', 'placeholder' => 'Enter father in law name', 'required' => true, 'col' => 4],
                ['name' => 'in_law_mother_name', 'label' => 'Mother in Law Name', 'placeholder' => 'Enter mother in law name', 'required' => true, 'col' => 4],
            ];

            return view('finance.customer.create', compact('branchs', 'fields'));
        });
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CustomerCreateRequest $request)
    {
        return $this->handleRequest(function () use ($request) {
            $validated = $request->validated();
            $customer_id = extractNumberPart($validated['aadhaar_number'], 8);
            if ($customer_id === "Invalid number") {
                return back()->withErrors(['aadhaar_number' => 'Invalid Aadhaar number']);
            }
            $validated['customer_id'] = $customer_id;
            $validated['added_by'] = Auth::id();
            $validated['status'] = 'active';

            $customerData = collect($validated)->except(['aadhaar_number', 'pan_number'])->toArray();

            $customer = $this->customerRepo->add($customerData);

            $this->saveDocument('aadhaar_number', $validated['aadhaar_number'], $customer->id);
            $this->saveDocument('pan_number', $validated['pan_number'], $customer->id);

            return redirect()->route('microfinance.customers.index');
        }, successMessage: 'customer_created_successfully');
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
            $branchs = $this->branchRepo->getList(dataLimit: 'all')->pluck('name', 'id')->toArray();
            $customer = $this->customerRepo->getFirstWhere(params: ['id' => $id], relations: ['document_identities']);
            $aadhaar = $customer->document_identities->where('name', 'aadhaar_number')->first()['number'];
            $pan = $customer->document_identities->where('name', 'pan_number')->first()['number'];

            $fields = [
                // Aadhaar & PAN
                ['name' => 'aadhaar_number__', 'label' => 'Aadhaar Number', 'placeholder' => 'Enter aadhaar number', 'required' => false,  'value' => $aadhaar, 'disabled' => true],
                ['name' => 'pan_number__', 'label' => 'PAN Number', 'placeholder' => 'Enter PAN number', 'required' => false, 'value' => $pan, 'disabled' => true],

                // Names
                ['name' => 'f_name', 'label' => 'First Name', 'placeholder' => 'Enter first name', 'required' => true, 'col' => 4, 'value' => $customer->f_name],
                ['name' => 'm_name', 'label' => 'Middle Name', 'placeholder' => 'Enter middle name', 'col' => 4, 'value' => $customer->m_name],
                ['name' => 'l_name', 'label' => 'Last Name', 'placeholder' => 'Enter last name', 'required' => true, 'col' => 4, 'value' => $customer->l_name],

                // Contact
                ['name' => 'mobile', 'label' => 'Mobile Number', 'placeholder' => 'Enter mobile number', 'type' => 'tel', 'required' => true, 'col' => 4, 'value' => $customer->mobile],
                ['name' => 'email', 'label' => 'Email', 'placeholder' => 'Enter email', 'type' => 'email', 'col' => 4, 'value' => $customer->email],

                // DOB + Gender + Branch
                ['name' => 'DOB', 'label' => 'Date of Birth', 'type' => 'date', 'required' => true, 'col' => 4, 'value' => $customer->DOB],
                ['name' => 'gender', 'label' => 'Gender', 'type' => 'select', 'options' => genders(), 'required' => true, 'col' => 4, 'value' => $customer->gender],
                ['name' => 'branch_id', 'label' => 'Branch', 'type' => 'select', 'options' => $branchs, 'required' => true, 'col' => 4, 'value' => $customer->branch_id],

                // Parents
                ['name' => 'father_name', 'label' => 'Father Name', 'placeholder' => 'Enter father name', 'required' => true, 'col' => 4, 'value' => $customer->father_name],
                ['name' => 'mother_name', 'label' => 'Mother Name', 'placeholder' => 'Enter mother name', 'required' => true, 'col' => 4, 'value' => $customer->mother_name],
                ['name' => 'in_law_father_name', 'label' => 'Father in Law Name', 'placeholder' => 'Enter father in law name', 'required' => true, 'col' => 4, 'value' => $customer->in_law_father_name],
                ['name' => 'in_law_mother_name', 'label' => 'Mother in Law Name', 'placeholder' => 'Enter mother in law name', 'required' => true, 'col' => 4, 'value' => $customer->in_law_mother_name],
            ];

            return view('finance.customer.edit', compact('fields', 'customer'));
        });
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CustomerUpdateRequest $request, string $id)
    {
        return $this->handleRequest(function () use ($request, $id) {
            $validated = $request->validated();

            $customerData = collect($validated)->except(['aadhaar_number', 'pan_number', 'customer_id'])->toArray();

            $this->customerRepo->update(id: $id, data: $customerData);
            return redirect()->route('microfinance.customers.index');
        }, successMessage: 'customer_update_successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        
    }
}
