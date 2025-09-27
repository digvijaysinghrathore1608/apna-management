<?php

namespace App\Models\Finance;

use App\Models\BaseModel as Model;

class Document extends Model
{
    protected $table = "finance_loan_documents";
    protected $fillable = ['loan_id', 'document_name', 'document_url'];
}
