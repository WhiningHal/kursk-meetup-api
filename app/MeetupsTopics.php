<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Meetup;
use App\Topic;
use App\People;

class MeetupsTopics extends Model
{
    protected $table = 'meetups_topics';
    protected $primaryKey = ['meetup_id','topic_id'];
    public function Meetup()
    {
        return $this->belongsTo('App\Meetup','meetup_id','id')->get();
    }
    public function Topic()
    {
        return Topic::find($this->attributes['topic_id']);
    }
    public function Person()
    {
        return People::find($this->attributes['person_id']);
    }
}
