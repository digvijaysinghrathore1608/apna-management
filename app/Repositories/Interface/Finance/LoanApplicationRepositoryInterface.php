<?php

namespace App\Repositories\Interface\Finance;

use App\Repositories\Interface\RepositoryInterface;
use Illuminate\Http\Request;

interface LoanApplicationRepositoryInterface extends RepositoryInterface
{
    public function updateOrCreateWithRelations(?int $id, array $data);
}
