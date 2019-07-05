<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Entry extends Model
{   
    protected $fillable = [
        'id_provider', 
        'id_user',
        'type_voucher',
        'voucher_series',
        'voucher_num',
        'date_hour',
        'taxes',
        'total',
        'status'
     ];
     public function user()
     {
         return $this->belongsTo('App\User');
     }
     public function provider()
     {
         return $this->belongsTo('App\Provider');
     }
    
}
