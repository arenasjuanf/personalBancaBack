<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAhorrosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ahorros', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('tipo');
            $table->unsignedBigInteger('fk_id_usuario');
            $table->string('nombre', 50);
            $table->integer('objetivo');
            $table->integer('ahorrado');
            $table->integer('intervalo')->nullable()->default(NULL);
            $table->integer('tipo_ahorro');
            $table->date("fechaMeta")->nullable()->default(NULL);
            $table->timestamps();

            $table->foreign('fk_id_usuario')->references('id')->on('usuarios');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ahorros');
    }
}
