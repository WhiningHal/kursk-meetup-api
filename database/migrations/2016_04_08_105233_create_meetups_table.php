<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMeetupsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('meetups', function (Blueprint $table) {
            $table->increments('id');
            $table->dateTime('meetupdatetime')->unique();
            $table->integer('place_id');
            $table->string('note',512)->nullable();
            $table->foreign('place_id')->references('id')->on('places');
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
        Schema::drop('meetups');
    }
}
