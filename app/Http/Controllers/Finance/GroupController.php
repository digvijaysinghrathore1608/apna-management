<?php

namespace App\Http\Controllers\Finance;

use App\Http\Controllers\BaseController as Controller;
use App\Http\Requests\Finance\GroupCreateRequest;
use App\Http\Requests\Finance\GroupUpdateRequest;
use App\Repositories\Interface\Finance\BranchRepositoryInterface;
use App\Repositories\Interface\Finance\CustomerRepositoryInterface;
use App\Repositories\Interface\Finance\GroupMemberRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GroupController extends Controller
{
    public function __construct(
        private readonly GroupMemberRepositoryInterface $groupMemberRepo,
        private readonly CustomerRepositoryInterface $customerRepo,
        private readonly BranchRepositoryInterface $branchRepo,
    ) {}
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        return $this->handleRequest(function () use ($request) {
            $result = $this->groupMemberRepo->getDataTable($request);
            if ($result) {
                return $result;
            }
            return view('finance.group.index');
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
            $branchs = $this->branchRepo->getList(dataLimit: 'all')->pluck('name', 'id')->toArray();
            $fields = [
                ['name' => 'customer_id', 'label' => 'Customer id', 'placeholder' => 'Customer Id', 'type' => 'select', 'options' => $customers, 'required' => true],
                ['name' => 'branch_id', 'label' => 'Branch', 'placeholder' => 'Branch', 'type' => 'select', 'options' => $branchs, 'required' => true],
                ['name' => 'area', 'label' => 'Area', 'placeholder' => 'Enter Area', 'required' => true],
            ];

            return view('finance.group.create', compact('fields'));
        });
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(GroupCreateRequest $request)
    {
        return $this->handleRequest(function () use ($request) {
            $validated = $request->validated();
            $validated['added_by'] = Auth::id();
            $validated['group_id'] = $this->uniqueIdGenerator()->generate('finance_loan_group_members', 'GR', column: 'group_id', padLength: 6);

            $this->groupMemberRepo->add($validated);
            return redirect()->route('microfinance.groups.index');
        }, successMessage: 'group_created_successfully');
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
            $customers = $this->customerRepo->getList(dataLimit: 'all')
                ->mapWithKeys(function ($item) {
                    return [
                        $item->id => $item->full_name . ' (' . $item->customer_id . ')'
                    ];
                })
                ->toArray();
            $branchs = $this->branchRepo->getList(dataLimit: 'all')->pluck('name', 'id')->toArray();
            $group = $this->groupMemberRepo->getFirstWhere(params: ['id' => $id]);
            $fields = [
                ['name' => 'customer_id', 'label' => 'Customer id', 'placeholder' => 'Customer Id', 'type' => 'select', 'options' => $customers, 'required' => true, 'value' => $group->customer_id],
                ['name' => 'branch_id', 'label' => 'Branch', 'placeholder' => 'Branch', 'type' => 'select', 'options' => $branchs, 'required' => true, 'value' => $group->branch_id],
                ['name' => 'area', 'label' => 'Area', 'placeholder' => 'Enter Area', 'required' => true, 'value' => $group->area],
            ];

            return view('finance.group.edit', compact('fields', 'group'));
        });
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(GroupUpdateRequest $request, string $id)
    {
        return $this->handleRequest(function () use ($request, $id) {
            $validated = $request->validated();

            $this->groupMemberRepo->update(id: $id, data: $validated);
            return redirect()->route('microfinance.groups.index');
        }, successMessage: 'group_update_successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        return $this->handleRequest(function () use ($id) {
            $this->groupMemberRepo->delete(['id' => $id]);
            return redirect()->route('microfinance.groups.index');
        }, successMessage: 'group_deleted_successfully');
    }
}
