<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRequestDetailTasksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('request_detail_tasks', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('requestdetail_id')->index();
            $table->string('avance', 5);
            $table->string('horas', 5);
            $table->date('dia');
            $table->integer('flag')->default();
            $table->dateTime('datetime_inicio');
            $table->dateTime('datetime_fin');
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
        Schema::dropIfExists('request_detail_tasks');
    }
}
