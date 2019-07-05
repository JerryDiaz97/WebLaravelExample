<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEntriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('entries', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('id_provider')->unsigned();
            $table->foreign('id_provider')->references('id')->on('providers');
            $table->integer('id_user')->unsigned();
            $table->foreign('id_user')->references('id')->on('users');
            $table->string('type_voucher', 20);
            $table->string('voucher_series', 7)->nullable();
            $table->string('voucher_num', 10);
            $table->dateTime('date_hour');
            $table->decimal('taxes', 4, 2);
            $table->decimal('total', 11, 2);
            $table->string('status', 20);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('entries');
    }
}
