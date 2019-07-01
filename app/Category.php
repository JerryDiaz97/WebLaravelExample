<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    //protected $table = 'categories';
    //protected $primaryKey = 'id';
    //For the mass assignment
    protected $fillable = ['name', 'description', 'condition'];
}
