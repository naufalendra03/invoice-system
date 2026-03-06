<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Customer;
use App\Models\Company;
use App\Models\SalesItem;
use App\Models\Payment;

class Sale extends Model
{

    protected $fillable = [

        'company_id',
        'customer_id',
        'invoice_number',
        'surat_jalan_number',
        'date',
        'due_date',
        'total',
        'status'

    ];


    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }


    public function company()
    {
        return $this->belongsTo(Company::class);
    }


    public function items()
    {
        return $this->hasMany(SalesItem::class);
    }


    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

}