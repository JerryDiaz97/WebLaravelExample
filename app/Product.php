<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [ 'idcategory', 'code', 'nameProd', 'sale_price', 'stock', 'descriptionProd', 'conditionProd'];
    public function category(){
        return $this->belongsTo('App\Category');
    }    
}
