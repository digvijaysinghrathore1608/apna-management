<?php

namespace App\Http\Controllers\Finance;

use App\Http\Controllers\BaseController;
use App\Http\Requests\Finance\ExpensesCreateRequest;
use App\Repositories\Interface\Finance\LoanExpenseRepositoryInterface;
use Devrabiul\ToastMagic\Facades\ToastMagic;
use Illuminate\Http\Request;

class ExpensesController extends BaseController
{

    public function __construct(
        private readonly LoanExpenseRepositoryInterface $loanExpenseRepo,
    ) {}

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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
    public function store(ExpensesCreateRequest $request)
    {
        return $this->handleRequest(function () use ($request) {
            $validated = $request->validated();
            $familyData = collect($validated)->toArray();
            $this->loanExpenseRepo->add($familyData);
            ToastMagic::success(translate('expense_added_successfully'));
            return redirect()->back();
        });
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
    public function update(ExpensesCreateRequest $request, string $id)
    {
        return $this->handleRequest(function () use ($request, $id) {
            $validated = $request->validated();
            $familyData = collect($validated)->toArray();
            $this->loanExpenseRepo->update($id, $familyData);
            ToastMagic::success(translate('expense_update_successfully'));
            return redirect()->back();
        });
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        return $this->handleRequest(function () use ($id) {
            $this->loanExpenseRepo->delete(['id' => $id]);
            ToastMagic::success(translate('successfully_delete_expense'));
            return redirect()->back();
        });
    }
}
