<?php

namespace App\Http\Controllers\Finance;

use App\Http\Controllers\BaseController as Controller;
use App\Repositories\Interface\Finance\BranchRepositoryInterface;
use App\Repositories\Interface\Finance\CustomerRepositoryInterface;
use App\Repositories\Interface\Finance\GroupMemberRepositoryInterface;
use App\Repositories\Interface\Finance\LoanApplicationRepositoryInterface;
use App\Repositories\Interface\Finance\LoanSchemaRepositoryInterface;
use Illuminate\Http\Request;

class LoanApplicationController extends Controller
{
    public function __construct(
        private readonly LoanApplicationRepositoryInterface $loanApplicationRepo,
        private readonly CustomerRepositoryInterface $customerRepo,
        private readonly GroupMemberRepositoryInterface $groupMemberRepo,
        private readonly BranchRepositoryInterface $branchRepo,
        private readonly LoanSchemaRepositoryInterface $loanSchemaRepo,
    ) {}
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        return $this->handleRequest(function () use ($request) {
            $result = $this->loanApplicationRepo->getDataTable($request);
            if ($result) {
                return $result;
            }
            return view('finance.loanapplication.index');
        });
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return $this->handleRequest(function () {
            $customers = $this->customerRepo->getList(dataLimit: 'all')
                ->mapWithKeys(function ($item) {
                    return [
                        $item->id => $item->full_name . ' (' . $item->customer_id . ')'
                    ];
                })
                ->toArray();

            $groups = $this->groupMemberRepo->getList(dataLimit: 'all')
                ->mapWithKeys(function ($item) {
                    return [
                        $item->id => $item->group_id . ' (' . $item->area . ')'
                    ];
                })
                ->toArray();
            $branchs = $this->branchRepo->getList(dataLimit: 'all')->pluck('name', 'id')->toArray();


            $fields = [
                // relations
                ['name' => 'customer_id', 'label' => 'Customer id', 'placeholder' => 'Customer Id', 'type' => 'select', 'options' => $customers, 'required' => true, 'col' => 4],
                ['name' => 'group_id', 'label' => 'group id', 'placeholder' => 'Group Id', 'type' => 'select', 'options' => $groups, 'required' => true, 'col' => 4],
                ['name' => 'branch_id', 'label' => 'Branch', 'placeholder' => 'Branch', 'type' => 'select', 'options' => $branchs, 'required' => true, 'col' => 4],

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

                // Parents
                ['name' => 'father_name', 'label' => 'Father Name', 'placeholder' => 'Enter father name', 'required' => true, 'col' => 4],
                ['name' => 'mother_name', 'label' => 'Mother Name', 'placeholder' => 'Enter mother name', 'required' => true, 'col' => 4],
                ['name' => 'in_law_father_name', 'label' => 'Father in Law Name', 'placeholder' => 'Enter father in law name', 'required' => true, 'col' => 4],
                ['name' => 'in_law_mother_name', 'label' => 'Mother in Law Name', 'placeholder' => 'Enter mother in law name', 'required' => true, 'col' => 4],
            ];

            return view('finance.loanapplication.create', compact('fields'));
        });
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
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
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
