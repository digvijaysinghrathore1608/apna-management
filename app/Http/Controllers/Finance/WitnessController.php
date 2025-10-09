<?php

namespace App\Http\Controllers\Finance;

use App\Http\Controllers\BaseController;
use App\Http\Requests\Finance\WitnessCreateRequest;
use App\Repositories\Interface\Finance\DocumentsIdRepositoryInterface;
use App\Repositories\Interface\Finance\LoanWitnessRepositoryInterface;
use Devrabiul\ToastMagic\Facades\ToastMagic;
use Illuminate\Http\Request;

class WitnessController extends BaseController
{
    public function __construct(
        private readonly LoanWitnessRepositoryInterface $loanWitnessRepo,
        private readonly DocumentsIdRepositoryInterface $documentsIdRepo,
    ) {}

    protected function saveDocument(string $name, string $number, int $relationId): void
    {
        $data = [
            'name' => $name,
            'number' => $number,
            'relation_table_name' => 'finance_loan_witness',
            'relation_id' => $relationId
        ];
        $this->documentsIdRepo->updateOrCreate([
            'name' => $name,
            'relation_table_name' => 'finance_loan_witness',
            'relation_id' => $relationId
        ], $data);
    }

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
    public function store(WitnessCreateRequest $request)
    {
        return $this->handleRequest(function () use ($request) {
            $validated = $request->validated();
            $familyData = collect($validated)->except(['aadhaar_number'])->toArray();
            $witness = $this->loanWitnessRepo->add($familyData);
            $this->saveDocument('aadhaar_number', $validated['aadhaar_number'], $witness->id);
            ToastMagic::success(translate('witness_added_successfully'));
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
    public function update(WitnessCreateRequest $request, string $id)
    {
        return $this->handleRequest(function () use ($request, $id) {
            $validated = $request->validated();
            $familyData = collect($validated)->except(['aadhaar_number'])->toArray();
            $this->loanWitnessRepo->update($id, $familyData);
            $witness = $this->loanWitnessRepo->getFirstWhere(['id' => $id]);
            $this->saveDocument('aadhaar_number', $validated['aadhaar_number'], $witness->id);
            ToastMagic::success(translate('witness_update_successfully'));
            return redirect()->back();
        });
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        return $this->handleRequest(function () use ($id) {
            $this->loanWitnessRepo->delete(['id' => $id]);
            ToastMagic::success(translate('successfully_delete_witness'));
            return redirect()->back();
        });
    }
}
