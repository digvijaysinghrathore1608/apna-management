<?php

namespace App\Models\Finance;

use App\Models\BaseModel as Model;

class DocumentIdentities extends Model
{
    protected $table = "finance_document_identities";
    protected $fillable = ['name', 'number', 'verified', 'relation_table_name', 'relation_id'];
}
