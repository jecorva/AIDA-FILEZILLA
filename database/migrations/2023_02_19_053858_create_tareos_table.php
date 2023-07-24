<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTareosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tareos', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('rdetailt_id')->index();
            $table->bigInteger('task_id')->index();
            $table->bigInteger('location_id')->index();
            $table->bigInteger('implement_id')->index();
            $table->bigInteger('operator_id')->index();
            $table->bigInteger('machinerie_id')->index();
            $table->bigInteger('state_id')->index();
            $table->string('horometro_inicio', 20);
            $table->string('horometro_fin', 20);
            $table->string('avance', 5);
            $table->string('obs_rejected', 200);
            $table->string('obs_update', 200);
            $table->integer('flag')->default();
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
        Schema::dropIfExists('tareos');
    }
}
