<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMeetupsTopicsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('meetups_topics', function (Blueprint $table) {
            $table->dateTime('meetup_date')->unsigned();
            $table->integer('topic_id')->unsigned();
            $table->integer('person_id')->unsigned();
            $table->primary(['meetup_date','topic_id']);
            $table->foreign('meetup_date')->references('datetime')->on('meetups');
            $table->foreign('topic_id')->references('id')->on('topics');
            $table->foreign('person_id')->references('id')->on('people');
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
        Schema::drop('meetups_topics');
    }
}
