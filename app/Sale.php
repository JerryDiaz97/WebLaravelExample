<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
    protected $fillable = [
        'id_client',
        'id_user',
        'type_voucherS',
        'voucher_seriesS',
        'voucher_numS',
        'date_hourS',
        'taxesS',
        'totalS',
        'status'
    ];
}
