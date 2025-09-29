<?php

namespace App\Repositories\Interface\Finance;

use App\Repositories\Interface\RepositoryInterface;
use Illuminate\Http\Request;

interface GroupMemberRepositoryInterface extends RepositoryInterface
{
    public function getDataTable(Request $request);
}
