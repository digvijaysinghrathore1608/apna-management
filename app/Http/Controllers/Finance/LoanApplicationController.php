<?php

namespace App\Http\Controllers\Finance;

use App\Http\Controllers\BaseController as Controller;
use App\Repositories\Interface\Finance\BranchRepositoryInterface;
use App\Repositories\Interface\Finance\CustomerRepositoryInterface;
use App\Repositories\Interface\Finance\GroupMemberRepositoryInterface;
use App\Repositories\Interface\Finance\LoanApplicationRepositoryInterface;
use App\Repositories\Interface\Finance\LoanSchemaRepositoryInterface;
use Devrabiul\ToastMagic\Facades\ToastMagic;
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
        //
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
        return $this->handleRequest(function () use ($id) {
            $loanApplication = $this->loanApplicationRepo->getFirstWhere(params: ['id' => $id], relations: ['customer']);
            if (!$loanApplication) {
                ToastMagic::error(translate('loan_not_found'));
                return back();
            }

            $prev_loan_application = $this->loanApplicationRepo->getFirstWhere(params: ['id' => $loanApplication->customer->current_loan]);

            $groups = $this->groupMemberRepo->getList(dataLimit: 'all')
                ->mapWithKeys(function ($item) {
                    return [
                        $item->id => $item->group_id . ' (' . $item->area . ')'
                    ];
                })
                ->toArray();
            $branchs = $this->branchRepo->getList(dataLimit: 'all')->pluck('name', 'id')->toArray();



            //make var

            $group_id = $loanApplication->group_id ?? $prev_loan_application?->group_id;
            $branch_id = $loanApplication->branch_id ?? $prev_loan_application?->branch_id;

            $f_name = $loanApplication->customer->f_name;
            $m_name = $loanApplication->customer->m_name;
            $l_name = $loanApplication->customer->l_name;
            $mobile = $loanApplication->customer->mobile;
            $email = $loanApplication->customer->email;
            $DOB = $loanApplication->customer->DOB;
            $gender = $loanApplication->customer->gender;
            $father_name = $loanApplication->customer->father_name;
            $mother_name = $loanApplication->customer->mother_name;
            $in_law_father_name = $loanApplication->customer->in_law_father_name;
            $in_law_mother_name = $loanApplication->customer->in_law_mother_name;


            $fields = [
                // relations
                ['name' => 'customer_id_', 'label' => 'Customer id', 'placeholder' => 'Customer Id', 'value' => $loanApplication->customer->customer_id, 'col' => 4, 'disabled' => true, 'readonly' => true],

                // Names
                ['name' => 'f_name_', 'label' => 'First Name', 'placeholder' => 'Enter first name', 'col' => 4, 'value' => $f_name, 'disabled' => true, 'readonly' => true],
                ['name' => 'm_name_', 'label' => 'Middle Name', 'placeholder' => 'Enter middle name', 'col' => 4, 'value' => $m_name, 'disabled' => true, 'readonly' => true],
                ['name' => 'l_name_', 'label' => 'Last Name', 'placeholder' => 'Enter last name', 'col' => 4, 'value' => $l_name, 'disabled' => true, 'readonly' => true],

                // Contact
                ['name' => 'mobile_', 'label' => 'Mobile Number', 'placeholder' => 'Enter mobile number', 'type' => 'tel', 'col' => 4, 'value' => $mobile, 'disabled' => true, 'readonly' => true],
                ['name' => 'email_', 'label' => 'Email', 'placeholder' => 'Enter email', 'type' => 'email', 'col' => 4, 'value' => $email, 'disabled' => true, 'readonly' => true],

                // DOB + Gender + Branch
                ['name' => 'DOB_', 'label' => 'Date of Birth', 'type' => 'date', 'col' => 4, 'value' => $DOB, 'disabled' => true, 'readonly' => true],
                ['name' => 'gender_', 'label' => 'Gender', 'type' => 'select', 'options' => genders(), 'col' => 4, 'value' => $gender, 'disabled' => true, 'readonly' => true],

                // Parents
                ['name' => 'father_name_', 'label' => 'Father Name', 'placeholder' => 'Enter father name', 'col' => 4, 'value' => $father_name, 'disabled' => true, 'readonly' => true],
                ['name' => 'mother_name_', 'label' => 'Mother Name', 'placeholder' => 'Enter mother name', 'col' => 4, 'value' => $mother_name, 'disabled' => true, 'readonly' => true],
                ['name' => 'in_law_father_name_', 'label' => 'Father in Law Name', 'placeholder' => 'Enter father in law name', 'col' => 4, 'value' => $in_law_father_name, 'disabled' => true, 'readonly' => true],
                ['name' => 'in_law_mother_name_', 'label' => 'Mother in Law Name', 'placeholder' => 'Enter mother in law name', 'col' => 4, 'value' => $in_law_mother_name, 'disabled' => true, 'readonly' => true],

                ['name' => 'group_id', 'label' => 'group id', 'placeholder' => 'Group Id', 'type' => 'select', 'options' => $groups, 'value' => $group_id, 'required' => true, 'col' => 4],
                ['name' => 'branch_id', 'label' => 'Branch', 'placeholder' => 'Branch', 'type' => 'select', 'options' => $branchs,  'value' => $branch_id, 'required' => true, 'col' => 4],

                ['type' => 'section', 'label' => 'Bank Details'],
                ['name' => 'account_holder_name', 'label' => 'account holder name', 'placeholder' => 'Enter account holder name', 'col' => 4, 'value' => '', 'required' => true],
                ['name' => 'account_number', 'label' => 'account number', 'placeholder' => 'Enter account number', 'col' => 4, 'value' => '', 'required' => true],
                ['name' => 'ifsc_code', 'label' => 'account IFSC code', 'placeholder' => 'Enter account IFSC code', 'col' => 4, 'value' => '', 'required' => true],
                ['name' => 'bank_name', 'label' => 'bank name', 'placeholder' => 'Enter bank name', 'col' => 4, 'value' => '', 'required' => true],
                ['name' => 'branch_name', 'label' => 'branch name', 'placeholder' => 'Enter branch name', 'col' => 4, 'value' => '', 'required' => true],
                ['name' => 'account_type', 'label' => 'account_type', 'placeholder' => 'Enter account type', 'type' => 'select', 'options' => ACCOUNT_TYPE, 'col' => 4, 'value' => '', 'required' => true],

                ['type' => 'section', 'label' => 'Current Address', 'name_prefix' => 'c'],

                ['name' => 'c_a_line_1', 'label' => 'current address line 1', 'placeholder' => 'Enter current address line 1', 'col' => 6, 'value' => '', 'required' => true],
                ['name' => 'c_a_line_2', 'label' => 'current address line 2', 'placeholder' => 'Enter current address line 2', 'col' => 6, 'value' => '',],
                ['name' => 'c_a_pincode', 'label' => 'current address pincode', 'placeholder' => 'Enter current address pincode', 'col' => 4, 'value' => '', 'required' => true],
                ['name' => 'c_a_city', 'label' => 'current address city', 'placeholder' => 'Enter current address city', 'col' => 4, 'value' => '', 'required' => true],
                ['name' => 'c_a_state', 'label' => 'current address state', 'placeholder' => 'Enter current address state', 'col' => 4, 'value' => '', 'required' => true],

                ['type' => 'section', 'label' => 'In Law Address', 'name_prefix' => 'in_law'],

                ['name' => 'in_law_a_line_1', 'label' => 'in law address line 1', 'placeholder' => 'Enter in law address line 1', 'col' => 6, 'value' => '', 'required' => true],
                ['name' => 'in_law_a_line_2', 'label' => 'in law address line 2', 'placeholder' => 'Enter in law address line 2', 'col' => 6, 'value' => '',],
                ['name' => 'in_law_a_pincode', 'label' => 'in law address pincode', 'placeholder' => 'Enter in law address pincode', 'col' => 4, 'value' => '', 'required' => true],
                ['name' => 'in_law_a_city', 'label' => 'in law address city', 'placeholder' => 'Enter in law address city', 'col' => 4, 'value' => '', 'required' => true],
                ['name' => 'in_law_a_state', 'label' => 'in law address state', 'placeholder' => 'Enter in law address state', 'col' => 4, 'value' => '', 'required' => true],

                ['type' => 'section', 'label' => 'Mother’s Address', 'name_prefix' => 'p'],

                ['name' => 'p_a_line_1', 'label' => "mother's address line 1", 'placeholder' => "Enter mother's address line 1", 'col' => 6, 'value' => '', 'required' => true],
                ['name' => 'p_a_line_2', 'label' => "mother's address line 2", 'placeholder' => "Enter mother's address line 2", 'col' => 6, 'value' => '',],
                ['name' => 'p_a_pincode', 'label' => "mother's address pincode", 'placeholder' => "Enter mother's address pincode", 'col' => 4, 'value' => '', 'required' => true],
                ['name' => 'p_a_city', 'label' => "mother's address city", 'placeholder' => "Enter mother's address city", 'col' => 4, 'value' => '', 'required' => true],
                ['name' => 'p_a_state', 'label' => "mother's address state", 'placeholder' => "Enter mother's address state", 'col' => 4, 'value' => '', 'required' => true],
            ];

            $next_step = 'step1';
            return view('finance.loanapplication.edit', compact('fields', 'id', 'next_step'));
        });
    }

    public function edit_step2(string $id)
    {
        return $this->handleRequest(function () use ($id) {
            $fields = [
                ['type' => 'section', 'label' => 'Family Members'],
                [
                    'type' => 'repeatable',
                    'name' => 'family_members',
                    'fields' => [
                        ['name' => 'name', 'label' => 'Name', 'placeholder' => 'Enter name', 'col' => 3],
                        ['name' => 'relation', 'label' => 'Relation', 'placeholder' => 'Enter relation', 'col' => 3],
                        ['name' => 'age', 'label' => 'Age', 'placeholder' => 'Enter age', 'col' => 2, 'type' => 'number'],
                        ['name' => 'mobile', 'label' => 'Mobile', 'placeholder' => 'Enter mobile', 'col' => 4, 'type' => 'tel'],
                    ],
                    // 'value' => $existingMembers // <- yahan data inject
                ],
            ];

            $next_step = 'step2';
            return view('finance.loanapplication.edit', compact('fields', 'id', 'next_step'));
        });
    }
    public function edit_step3(string $id)
    {
        return $this->handleRequest(function () use ($id) {
            $fields = [
                ['type' => 'section', 'label' => 'Expenses'],
                [
                    'type' => 'repeatable',
                    'name' => 'expenses_detail',
                    'fields' => [
                        ['name' => 'expense_type', 'label' => 'expense_type', 'placeholder' => 'enter expense type', 'col' => 3],
                        ['name' => 'amount', 'label' => 'amount', 'placeholder' => 'Enter amount', 'col' => 1],
                        ['name' => 'remarks', 'label' => 'remarks', 'placeholder' => 'Enter remarks', 'col' => 3],
                    ],
                ],
            ];

            $next_step = 'step3';
            return view('finance.loanapplication.edit', compact('fields', 'id', 'next_step'));
        });
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        return redirect()->route('microfinance.loanapplication.edit.step2', $id);
    }
    public function update_step_2(Request $request, string $id)
    {
        return redirect()->route('microfinance.loanapplication.edit.step3', $id);
    }

    public function update_step_3(Request $request, string $id)
    {
        return redirect()->route('microfinance.loanapplication.edit.step1', $id);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
