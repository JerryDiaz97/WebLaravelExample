<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class IncomeDetail extends Model
{
    protected $table = 'income_details';
    protected $fillable = [
        'id_income', 
        'id_product',
        'amount',
        'price'
    ];
    public $timestamps = false;
}
