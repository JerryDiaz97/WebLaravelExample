<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    protected $fillable = ['namec','type_doc','doc_num','address','phone_num','email'];

    public function provider(){
        return $this->hasOne('App\Provider');
    }

    public function user(){
        return $this->hasOne('App\User');
    }
}
