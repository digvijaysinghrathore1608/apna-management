<?php

namespace App\Models\Invoice;

use App\Models\BaseModel;

class Invoice extends BaseModel
{
    protected $table = 'invoice';
    protected $fillable = [
        'template_id',
        'invoice_number',
        'invoice_date',
        'customer_name',
        'invoice_path',
        'status',
    ];

    public function template()
    {
        return $this->belongsTo(InvoiceTemplate::class, 'template_id');
    }
}
