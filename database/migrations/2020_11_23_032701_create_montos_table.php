<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMontosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('montos', function (Blueprint $table) {
            $table->bigIncrements("id");
            $table->unsignedBigInteger('fk_id_ahorro');
            $table->integer('valor');
            $table->integer('chec')->nullable()->default(NULL);
            $table->timestamps();

            $table->foreign('fk_id_ahorro')->references('id')->on('ahorros');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('montos');
    }
}
