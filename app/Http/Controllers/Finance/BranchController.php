<?php

namespace App\Http\Controllers\Finance;

use App\Http\Controllers\BaseController as Controller;
use App\Http\Requests\Finance\BranchCreateRequest;
use App\Http\Requests\Finance\BranchUpdateRequest;
use App\Repositories\Interface\Finance\BranchRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BranchController extends Controller
{
    public function __construct(
        private readonly BranchRepositoryInterface $branchRepo,
    ) {}
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        return $this->handleRequest(function () use ($request) {
            $result = $this->branchRepo->getDataTable($request);
            if ($result) {
                return $result;
            }
            return view('finance.branch.index');
        });
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return $this->handleRequest(function () {
            $fields = [
                // Names
                ['name' => 'code', 'label' => 'Code', 'placeholder' => 'Enter Branch code', 'required' => true, 'col' => 4],
                ['name' => 'name', 'label' => 'name', 'placeholder' => 'Enter Branch name', 'required' => true, 'col' => 4],

                // Contact
                ['name' => 'mobile', 'label' => 'Mobile Number', 'placeholder' => 'Enter mobile number', 'type' => 'tel', 'required' => true, 'col' => 4],
                ['name' => 'email', 'label' => 'Email', 'placeholder' => 'Enter email', 'type' => 'email', 'required' => true, 'col' => 4],

                ['name' => 'address', 'label' => 'Address', 'placeholder' => 'Enter Address', 'type' => 'textarea', 'required' => true],

            ];

            return view('finance.branch.create', compact('fields'));
        });
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(BranchCreateRequest $request)
    {
        return $this->handleRequest(function () use ($request) {
            $validated = $request->validated();
            $validated['added_by'] = Auth::id();

            $this->branchRepo->add($validated);
            return redirect()->route('microfinance.branch.index');
        }, successMessage: 'branch_created_successfully');
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
            $branch = $this->branchRepo->getFirstWhere(params: ['id' => $id]);
            $fields = [
                // Names
                ['name' => 'code', 'label' => 'Code', 'placeholder' => 'Enter Branch code', 'required' => true, 'col' => 4, 'value' => $branch->code],
                ['name' => 'name', 'label' => 'name', 'placeholder' => 'Enter Branch name', 'required' => true, 'col' => 4, 'value' => $branch->name],

                // Contact
                ['name' => 'mobile', 'label' => 'Mobile Number', 'placeholder' => 'Enter mobile number', 'type' => 'tel', 'required' => true, 'col' => 4, 'value' => $branch->mobile],
                ['name' => 'email', 'label' => 'Email', 'placeholder' => 'Enter email', 'type' => 'email', 'required' => true, 'col' => 4, 'value' => $branch->email],

                ['name' => 'address', 'label' => 'Address', 'placeholder' => 'Enter Address', 'type' => 'textarea', 'required' => true, 'value' => $branch->address],

            ];

            return view('finance.branch.edit', compact('fields', 'branch'));
        });
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(BranchUpdateRequest $request, string $id)
    {
        return $this->handleRequest(function () use ($request, $id) {
            $validated = $request->validated();

            $this->branchRepo->update(id: $id, data: $validated);
            return redirect()->route('microfinance.branch.index');
        }, successMessage: 'branch_update_successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        return $this->handleRequest(function () use ($id) {
            $this->branchRepo->delete(['id' => $id]);
            return redirect()->route('microfinance.branch.index');
        }, successMessage: 'branch_deleted_successfully');
    }
}
