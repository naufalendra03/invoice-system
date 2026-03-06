<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Sale;
use App\Models\Product;

class SalesItem extends Model
{

    protected $table = 'sales_items';

    protected $fillable = [

        'sale_id',
        'product_id',
        'price',
        'qty',
        'subtotal'

    ];


    // RELASI KE INVOICE
    public function sale()
    {

        return $this->belongsTo(Sale::class);

    }


    // RELASI KE PRODUCT
    public function product()
    {

        return $this->belongsTo(Product::class);

    }

}