<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SalesDetail extends Model
{   
    protected $table = 'sales_details';
    protected $fillable = [
        'id_venta',
        'id_product',
        'amount',
        'price',
        'discount'
    ];
}
