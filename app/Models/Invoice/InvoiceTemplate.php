<?php

namespace App\Models\Invoice;

use App\Models\BaseModel;

class InvoiceTemplate extends BaseModel
{
    protected $table = 'invoice_template';
    protected $fillable = ['name', 'description'];
}
