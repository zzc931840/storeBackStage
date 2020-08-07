<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTicksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ticks', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('start',255);
            $table->string('end',255);
            $table->string('StartTime',255);
            $table->string('EndTime',255);
            $table->string('Price',255);
            $table->string('whether',20);
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
        Schema::dropIfExists('ticks');
    }
}
