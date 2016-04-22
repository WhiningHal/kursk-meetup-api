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
            $table->integer('meetup_id');
            $table->integer('topic_id');
            $table->integer('person_id');
            $table->primary(['meetup_id','topic_id']);
            $table->foreign('meetup_id')->references('id')->on('meetups');
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
