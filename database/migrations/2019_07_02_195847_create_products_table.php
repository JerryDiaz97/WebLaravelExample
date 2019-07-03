<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('idcategory')->unsigned();
            $table->string('code', 50)->nullable();
            $table->string('nameProd', 100)->unique();
            $table->decimal('sale_price', 11, 2);
            $table->integer('stock');
            $table->string('descriptionProd', 256)->nullable();
            $table->boolean('conditionProd')->default(1);
            $table->timestamps();

            $table->foreign('idcategory')->references('id')->on('categories');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('products');
    }
}
