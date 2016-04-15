<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateContentTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('content', function (Blueprint $table) {
            $table->integer('topic_id')->unsigned();
            $table->integer('ordinal_nr');
            $table->string('url',255);
            $table->string('title',255);
            $table->timestamps();
            $table->foreign('topic_id')->references('id')->on('topics');
            $table->primary(['topic_id','ordinal_nr']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('content');
    }
}
