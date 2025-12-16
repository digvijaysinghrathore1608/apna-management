<?php

namespace App\Http\Controllers\Finance;

use App\Http\Controllers\BaseController as Controller;
use App\Http\Requests\Finance\LoanApplicationUpdateStep1Request;
use App\Repositories\Interface\Finance\AddressRepositoryInterface;
use App\Repositories\Interface\Finance\BranchRepositoryInterface;
use App\Repositories\Interface\Finance\CustomerRepositoryInterface;
use App\Repositories\Interface\Finance\DocumentsRepositoryInterface;
use App\Repositories\Interface\Finance\FamilyMemberRepositoryInterface;
use App\Repositories\Interface\Finance\GroupMemberRepositoryInterface;
use App\Repositories\Interface\Finance\LoanApplicationRepositoryInterface;
use App\Repositories\Interface\Finance\LoanExpenseRepositoryInterface;
use App\Repositories\Interface\Finance\LoanSchemaRepositoryInterface;
use App\Repositories\Interface\Finance\LoanWitnessRepositoryInterface;
use Devrabiul\ToastMagic\Facades\ToastMagic;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use function PHPSTORM_META\type;

class LoanApplicationController extends Controller
{
    public function __construct(
        private readonly LoanApplicationRepositoryInterface $loanApplicationRepo,
        private readonly CustomerRepositoryInterface $customerRepo,
        private readonly GroupMemberRepositoryInterface $groupMemberRepo,
        private readonly BranchRepositoryInterface $branchRepo,
        private readonly LoanSchemaRepositoryInterface $loanSchemaRepo,
        private readonly AddressRepositoryInterface $addressRepo,
        private readonly FamilyMemberRepositoryInterface $familyMemberRepo,
        private readonly LoanExpenseRepositoryInterface $loanExpenseRepo,
        private readonly LoanWitnessRepositoryInterface $loanWitnessRepo,
        private readonly DocumentsRepositoryInterface $documentsRepo,
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
            $loanApplication = $this->loanApplicationRepo->getFirstWhere(params: ['id' => $id], relations: ['customer', 'bank_detail', 'address']);
            if (!$loanApplication) {
                ToastMagic::error(translate('loan_not_found'));
                return back();
            }

            $prev_loan_application = $this->loanApplicationRepo->getFirstWhere(params: ['id' => $loanApplication->customer->current_loan], relations: ['bank_detail', 'address']);

            $groups = $this->groupMemberRepo->getList(dataLimit: 'all')
                ->mapWithKeys(function ($item) {
                    return [
                        $item->id => $item->group_id . ' (' . $item->area . ')'
                    ];
                })
                ->toArray();
            $branchs = $this->branchRepo->getList(dataLimit: 'all')->pluck('name', 'id')->toArray();
            $loan_schemas = $this->loanSchemaRepo->getList(dataLimit: 'all')->pluck('name', 'id')->toArray();
            //make var

            $group_id = $loanApplication->group_id ?? $prev_loan_application?->group_id;
            $branch_id = $loanApplication->branch_id ?? $prev_loan_application?->branch_id;
            $loan_schemas_id = $loanApplication->loan_schema ?? $prev_loan_application?->loan_schema;

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

            $account_holder_name = $loanApplication?->bank_detail?->account_holder_name ?? $prev_loan_application?->bank_detail?->account_holder_name;
            $account_number = $loanApplication?->bank_detail?->account_number ?? $prev_loan_application?->bank_detail?->account_number;
            $ifsc_code = $loanApplication?->bank_detail?->ifsc_code ?? $prev_loan_application?->bank_detail?->ifsc_code;
            $bank_name = $loanApplication?->bank_detail?->bank_name ?? $prev_loan_application?->bank_detail?->bank_name;
            $branch_name = $loanApplication?->bank_detail?->branch_name ?? $prev_loan_application?->bank_detail?->branch_name;
            $account_type = $loanApplication?->bank_detail?->account_type ?? $prev_loan_application?->bank_detail?->account_type;

            $c_a_line_1 = $loanApplication?->address?->c_a_line_1 ?? $prev_loan_application?->address?->c_a_line_1;
            $c_a_line_2 = $loanApplication?->address?->c_a_line_2 ?? $prev_loan_application?->address?->c_a_line_2;
            $c_a_pincode = $loanApplication?->address?->c_a_pincode ?? $prev_loan_application?->address?->c_a_pincode;
            $c_a_city = $loanApplication?->address?->c_a_city ?? $prev_loan_application?->address?->c_a_city;
            $c_a_state = $loanApplication?->address?->c_a_state ?? $prev_loan_application?->address?->c_a_state;
            $in_law_a_line_1 = $loanApplication?->address?->in_law_a_line_1 ?? $prev_loan_application?->address?->in_law_a_line_1;
            $in_law_a_line_2 = $loanApplication?->address?->in_law_a_line_2 ?? $prev_loan_application?->address?->in_law_a_line_2;
            $in_law_a_pincode = $loanApplication?->address?->in_law_a_pincode ?? $prev_loan_application?->address?->in_law_a_pincode;
            $in_law_a_city = $loanApplication?->address?->in_law_a_city ?? $prev_loan_application?->address?->in_law_a_city;
            $in_law_a_state = $loanApplication?->address?->in_law_a_state ?? $prev_loan_application?->address?->in_law_a_state;
            $p_a_line_1 = $loanApplication?->address?->p_a_line_1 ?? $prev_loan_application?->address?->p_a_line_1;
            $p_a_line_2 = $loanApplication?->address?->p_a_line_2 ?? $prev_loan_application?->address?->p_a_line_2;
            $p_a_pincode = $loanApplication?->address?->p_a_pincode ?? $prev_loan_application?->address?->p_a_pincode;
            $p_a_city = $loanApplication?->address?->p_a_city ?? $prev_loan_application?->address?->p_a_city;
            $p_a_state = $loanApplication?->address?->p_a_state ?? $prev_loan_application?->address?->p_a_state;


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
                ['name' => 'loan_schema', 'label' => 'Loan Schema', 'placeholder' => 'Loan Schema', 'type' => 'select', 'options' => $loan_schemas,  'value' => $loan_schemas_id, 'required' => true, 'col' => 4],

                ['type' => 'section', 'label' => 'Bank Details'],
                ['name' => 'account_holder_name', 'label' => 'account holder name', 'placeholder' => 'Enter account holder name', 'col' => 4, 'value' => $account_holder_name, 'required' => true],
                ['name' => 'account_number', 'label' => 'account number', 'placeholder' => 'Enter account number', 'col' => 4, 'value' => $account_number, 'required' => true],
                ['name' => 'ifsc_code', 'label' => 'account IFSC code', 'placeholder' => 'Enter account IFSC code', 'col' => 4, 'value' => $ifsc_code, 'required' => true],
                ['name' => 'bank_name', 'label' => 'bank name', 'placeholder' => 'Enter bank name', 'col' => 4, 'value' => $bank_name, 'required' => true],
                ['name' => 'branch_name', 'label' => 'branch name', 'placeholder' => 'Enter branch name', 'col' => 4, 'value' => $branch_name, 'required' => true],
                ['name' => 'account_type', 'label' => 'account_type', 'placeholder' => 'Enter account type', 'type' => 'select', 'options' => ACCOUNT_TYPE, 'col' => 4, 'value' => $account_type, 'required' => true],

                ['type' => 'section', 'label' => 'Current Address', 'name_prefix' => 'c'],

                ['name' => 'c_a_line_1', 'label' => 'current address line 1', 'placeholder' => 'Enter current address line 1', 'col' => 6, 'value' => $c_a_line_1, 'required' => true],
                ['name' => 'c_a_line_2', 'label' => 'current address line 2', 'placeholder' => 'Enter current address line 2', 'col' => 6, 'value' => $c_a_line_2,],
                ['name' => 'c_a_pincode', 'label' => 'current address pincode', 'placeholder' => 'Enter current address pincode', 'col' => 4, 'value' => $c_a_pincode, 'required' => true],
                ['name' => 'c_a_city', 'label' => 'current address city', 'placeholder' => 'Enter current address city', 'col' => 4, 'value' => $c_a_city, 'required' => true],
                ['name' => 'c_a_state', 'label' => 'current address state', 'placeholder' => 'Enter current address state', 'col' => 4, 'value' => $c_a_state, 'required' => true],

                ['type' => 'section', 'label' => 'In Law Address', 'name_prefix' => 'in_law'],

                ['name' => 'in_law_a_line_1', 'label' => 'in law address line 1', 'placeholder' => 'Enter in law address line 1', 'col' => 6, 'value' => $in_law_a_line_1, 'required' => true],
                ['name' => 'in_law_a_line_2', 'label' => 'in law address line 2', 'placeholder' => 'Enter in law address line 2', 'col' => 6, 'value' => $in_law_a_line_2,],
                ['name' => 'in_law_a_pincode', 'label' => 'in law address pincode', 'placeholder' => 'Enter in law address pincode', 'col' => 4, 'value' => $in_law_a_pincode, 'required' => true],
                ['name' => 'in_law_a_city', 'label' => 'in law address city', 'placeholder' => 'Enter in law address city', 'col' => 4, 'value' => $in_law_a_city, 'required' => true],
                ['name' => 'in_law_a_state', 'label' => 'in law address state', 'placeholder' => 'Enter in law address state', 'col' => 4, 'value' => $in_law_a_state, 'required' => true],

                ['type' => 'section', 'label' => 'Mother’s Address', 'name_prefix' => 'p'],

                ['name' => 'p_a_line_1', 'label' => "mother's address line 1", 'placeholder' => "Enter mother's address line 1", 'col' => 6, 'value' => $p_a_line_1, 'required' => true],
                ['name' => 'p_a_line_2', 'label' => "mother's address line 2", 'placeholder' => "Enter mother's address line 2", 'col' => 6, 'value' => $p_a_line_2,],
                ['name' => 'p_a_pincode', 'label' => "mother's address pincode", 'placeholder' => "Enter mother's address pincode", 'col' => 4, 'value' => $p_a_pincode, 'required' => true],
                ['name' => 'p_a_city', 'label' => "mother's address city", 'placeholder' => "Enter mother's address city", 'col' => 4, 'value' => $p_a_city, 'required' => true],
                ['name' => 'p_a_state', 'label' => "mother's address state", 'placeholder' => "Enter mother's address state", 'col' => 4, 'value' => $p_a_state, 'required' => true],
            ];

            $update_route = route('microfinance.loanapplication.edit.step1', $id);
            $submit_label = 'Save and Continue to Step 2';
            $submit_disable = false;
            return view('finance.loanapplication.edit', compact('fields', 'id', 'update_route', 'submit_label', 'submit_disable'));
        });
    }

    public function edit_step2(string $id)
    {
        return $this->handleRequest(function () use ($id) {
            $loanApplication = $this->loanApplicationRepo->getFirstWhere(params: ['id' => $id]);
            if (!$loanApplication) {
                ToastMagic::error(translate('loan_not_found'));
                return back();
            }

            $familyMembers = $this->familyMemberRepo->getListWhere(dataLimit: 'all', filters: ['customer_id' => $loanApplication->customer_id]);
            $next_step_allow = count($familyMembers) > 0;



            $familyMembers[] = ['customer_id' => $loanApplication->customer_id];
            $fields = [
                ['type' => 'section', 'label' => 'Family Members'],
                [
                    'type' => 'repeatable',
                    'name' => 'family_members',
                    'fields' => [
                        ['name' => 'name', 'label' => 'Name', 'placeholder' => 'Enter name', 'required' => true],
                        ['name' => 'dob', 'label' => 'Date of Birth', 'type' => 'date', 'required' => true],
                        ['name' => 'mobile', 'label' => 'Mobile', 'placeholder' => 'Enter mobile', 'type' => 'tel', 'required' => true],
                        ['name' => 'relation', 'label' => 'Relation', 'placeholder' => 'Enter relation', 'required' => true],
                        ['name' => 'work_type', 'label' => 'work_type', 'placeholder' => 'Enter work type', 'type' => 'select', 'options' => work_type(), 'required' => true],
                        ['name' => 'work_detail', 'label' => 'work_detail', 'placeholder' => 'Enter work_detail', 'required' => true],
                        ['name' => 'monthly_income', 'label' => 'monthly_income', 'placeholder' => 'Enter monthly income', 'required' => true],
                        ['name' => 'address_line_1', 'label' => 'address_line_1', 'placeholder' => 'Enter address line 1', 'required' => true],
                        ['name' => 'address_line_2', 'label' => 'address_line_2', 'placeholder' => 'Enter address_line_2',],
                        ['name' => 'address_city', 'label' => 'address_city', 'placeholder' => 'Enter address_city', 'required' => true],
                        ['name' => 'address_state', 'label' => 'address_state', 'placeholder' => 'Enter address_state', 'required' => true],
                        ['name' => 'address_pincode', 'label' => 'address_pincode', 'placeholder' => 'Enter address_pincode', 'required' => true],
                        ['name' => 'is_nominee', 'label' => 'is_nominee', 'placeholder' => 'Enter is_nominee', 'type' => 'switch'],
                        ['name' => 'customer_id', 'label' => '', 'placeholder' => '', 'type' => 'hidden'],
                    ],
                    'value' =>  $familyMembers, // Existing data
                    'actions' => [
                        'store'  => ['route' => 'microfinance.customers.family.store'],
                        'update' => ['route' => 'microfinance.customers.family.update'],
                        'delete' => ['route' => 'microfinance.customers.family.destroy'],
                    ]
                ],
            ];

            $next_step_route = route('microfinance.loanapplication.edit.step3', $id);
            $back_step_route = route('microfinance.loanapplication.edit', $id);
            return view('finance.loanapplication.step-edit', compact('fields', 'id', 'next_step_allow', 'next_step_route', 'back_step_route'));
        });
    }
    public function edit_step3(string $id)
    {
        return $this->handleRequest(function () use ($id) {
            $loanApplication = $this->loanApplicationRepo->getFirstWhere(params: ['id' => $id]);
            if (!$loanApplication) {
                ToastMagic::error(translate('loan_not_found'));
                return back();
            }
            $expenses = $this->loanExpenseRepo->getListWhere(dataLimit: 'all', filters: ['loan_id' => $loanApplication->id]);
            $next_step_allow = count($expenses) > 0;

            $expenses[] = ['loan_id' => $loanApplication->id];
            $fields = [
                ['type' => 'section', 'label' => 'Expenses'],
                [
                    'type' => 'repeatable',
                    'name' => 'expenses_detail',
                    'fields' => [
                        ['name' => 'expense_type', 'label' => 'expense_type', 'placeholder' => 'Enter expense_type', 'required' => true],
                        ['name' => 'amount', 'label' => 'amount', 'placeholder' => 'Enter amount', 'required' => true],
                        ['name' => 'remarks', 'label' => 'remarks', 'placeholder' => 'Enter remarks'],
                        ['name' => 'loan_id', 'label' => '', 'placeholder' => '', 'type' => 'hidden'],
                    ],
                    'value' =>  $expenses, // Existing data
                    'actions' => [
                        'store'  => ['route' => 'microfinance.loanapplication.expenses.store'],
                        'update' => ['route' => 'microfinance.loanapplication.expenses.update'],
                        'delete' => ['route' => 'microfinance.loanapplication.expenses.destroy'],
                    ]
                ],
            ];

            $next_step_route = route('microfinance.loanapplication.edit.step4', $id);
            $back_step_route = route('microfinance.loanapplication.edit.step2', $id);
            return view('finance.loanapplication.step-edit', compact('fields', 'id', 'next_step_allow', 'next_step_route', 'back_step_route'));
        });
    }

    public function edit_step4(string $id)
    {
        return $this->handleRequest(function () use ($id) {
            $loanApplication = $this->loanApplicationRepo->getFirstWhere(params: ['id' => $id]);
            if (!$loanApplication) {
                ToastMagic::error(translate('loan_not_found'));
                return back();
            }
            $witness = $this->loanWitnessRepo->getListWhere(dataLimit: 'all', filters: ['loan_id' => $loanApplication->id]);
            $next_step_allow = count($witness) > 0;

            $witness[] = ['loan_id' => $loanApplication->id];
            $fields = [
                ['type' => 'section', 'label' => 'Witness'],
                [
                    'type' => 'repeatable',
                    'name' => 'witness_detail',
                    'fields' => [
                        ['name' => 'name', 'label' => 'name', 'placeholder' => 'Enter name', 'required' => true],
                        ['name' => 'mobile', 'label' => 'mobile', 'placeholder' => 'Enter mobile', 'required' => true],
                        ['name' => 'dob', 'label' => 'Date of Birth', 'type' => 'date', 'required' => true],
                        ['name' => 'aadhaar_number', 'label' => 'aadhaar_number', 'placeholder' => 'Enter aadhaar_number', 'required' => true],
                        ['name' => 'guardian_name', 'label' => 'guardian_name', 'placeholder' => 'Enter guardian_name', 'required' => true],
                        ['name' => 'address_line_1', 'label' => 'address_line_1', 'placeholder' => 'Enter address_line_1', 'required' => true],
                        ['name' => 'address_line_2', 'label' => 'address_line_2', 'placeholder' => 'Enter address_line_2'],
                        ['name' => 'address_city', 'label' => 'address_city', 'placeholder' => 'Enter address_city', 'required' => true],
                        ['name' => 'address_state', 'label' => 'address_state', 'placeholder' => 'Enter address_state', 'required' => true],
                        ['name' => 'address_pincode', 'label' => 'address_pincode', 'placeholder' => 'Enter address_pincode', 'required' => true],
                        ['name' => 'loan_id', 'label' => '', 'placeholder' => '', 'type' => 'hidden'],
                    ],
                    'value' =>  $witness, // Existing data
                    'actions' => [
                        'store'  => ['route' => 'microfinance.loanapplication.witness.store'],
                        'update' => ['route' => 'microfinance.loanapplication.witness.update'],
                        'delete' => ['route' => 'microfinance.loanapplication.witness.destroy'],
                    ]
                ],
            ];

            $next_step_route = '#';
            $back_step_route = route('microfinance.loanapplication.edit.step3', $id);
            return view('finance.loanapplication.step-edit', compact('fields', 'id', 'next_step_allow', 'next_step_route', 'back_step_route'));
        });
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(LoanApplicationUpdateStep1Request $request, string $id)
    {
        return $this->handleRequest(function () use ($request, $id) {
            $loanApplication = $this->loanApplicationRepo->getFirstWhere(params: ['id' => $id]);
            if (!$loanApplication) {
                ToastMagic::error(translate('loan_not_found'));
                return back();
            }

            $validated = $request->validated();
            $validated['added_by'] = Auth::id();

            $data = [
                'application' => [
                    'group_id' => $validated['group_id'],
                    'branch_id' => $validated['branch_id'],
                    'loan_schema' => $validated['loan_schema'],
                ],
                'address' => [
                    'c_a_line_1' => $validated['c_a_line_1'],
                    'c_a_line_2' => $validated['c_a_line_2'],
                    'c_a_pincode' => $validated['c_a_pincode'],
                    'c_a_city' => $validated['c_a_city'],
                    'c_a_state' => $validated['c_a_state'],
                    'in_law_a_line_1' => $validated['in_law_a_line_1'],
                    'in_law_a_line_2' => $validated['in_law_a_line_2'],
                    'in_law_a_pincode' => $validated['in_law_a_pincode'],
                    'in_law_a_city' => $validated['in_law_a_city'],
                    'in_law_a_state' => $validated['in_law_a_state'],
                    'p_a_line_1' => $validated['p_a_line_1'],
                    'p_a_line_2' => $validated['p_a_line_2'],
                    'p_a_pincode' => $validated['p_a_pincode'],
                    'p_a_city' => $validated['p_a_city'],
                    'p_a_state' => $validated['p_a_state'],
                ],
                'bank_detail' => [
                    'account_holder_name' => $validated['account_holder_name'],
                    'account_number' => $validated['account_number'],
                    'ifsc_code' => $validated['ifsc_code'],
                    'bank_name' => $validated['bank_name'],
                    'branch_name' => $validated['branch_name'],
                    'account_type' => $validated['account_type'],
                ]
            ];

            $response = $this->loanApplicationRepo->updateOrCreateWithRelations(id: $id, data: $data);

            if (!$response) {
                ToastMagic::error(translate('loan_not_found'));
                return back();
            }
            ToastMagic::success(translate('loan_step1_complete'));
            return redirect()->route('microfinance.loanapplication.edit.step2', $id);
        });
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
