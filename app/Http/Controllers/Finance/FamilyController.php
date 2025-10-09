<?php

namespace App\Http\Controllers\Finance;

use App\Http\Controllers\BaseController;
use App\Http\Requests\Finance\FamilyMemberCreateRequest;
use App\Repositories\Interface\Finance\FamilyMemberRepositoryInterface;
use App\Repositories\Interface\Finance\LoanApplicationRepositoryInterface;
use Devrabiul\ToastMagic\Facades\ToastMagic;
use Illuminate\Http\Request;

class FamilyController extends BaseController
{
    public function __construct(
        private readonly FamilyMemberRepositoryInterface $familyMemberRepo,
        private readonly LoanApplicationRepositoryInterface $loanApplicationRepo,
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
    public function store(FamilyMemberCreateRequest $request)
    {
        return $this->handleRequest(function () use ($request) {
            $validated = $request->validated();
            $familyData = collect($validated)->toArray();
            $member = $this->familyMemberRepo->add($familyData);
            if ($member->is_nominee) {
                $this->loanApplicationRepo->update($validated['current_loan'], ['nominee' => $member->id]);
            }
            ToastMagic::success(translate('member_added_successfully'));
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
    public function update(FamilyMemberCreateRequest $request, string $id)
    {
        return $this->handleRequest(function () use ($request, $id) {
            $validated = $request->validated();
            $familyData = collect($validated)->toArray();
            $this->familyMemberRepo->update($id, $familyData);
            $member = $this->familyMemberRepo->getFirstWhere(['id' => $validated['current_loan']]);
            if ($member->is_nominee) {
                $this->loanApplicationRepo->update($validated['current_loan'], ['nominee' => $member->id]);
            }
            ToastMagic::success(translate('member_update_successfully'));
            return redirect()->back();
        });
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        return $this->handleRequest(function () use ($id) {
            $this->familyMemberRepo->delete(['id' => $id]);
            ToastMagic::success(translate('successfully_delete_member'));
            return redirect()->back();
        });
    }
}
