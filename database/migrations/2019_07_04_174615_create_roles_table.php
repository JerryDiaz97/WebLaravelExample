<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRolesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('roles', function (Blueprint $table) {
            $table->increments('id');
            $table->string('namer', 30)->unique();
            $table->string('description', 100)->nullable();
            $table->boolean('condition')->default(1); 
        });
        DB::table('roles')->insert(array('id'=>'1','namer'=>'Administrador', 'description'=>'Administradores de área'));
        DB::table('roles')->insert(array('id'=>'2','namer'=>'Vendedor', 'description'=>'Vendedor área venta'));
        DB::table('roles')->insert(array('id'=>'3','namer'=>'Almacenista', 'description'=>'Almacenista área compras'));
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('roles');
    }
}
