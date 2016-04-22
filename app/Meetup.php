<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Log;
use App\Place;
use App\MeetupsTopics;

class Meetup extends Model
{
    public function Place()
    {
        return $this->belongsTo('App\Place')->get()[0];
    }
    public function StrDate()
    {
        return $this->meetupdatetime->format('Y-m-d H:i:s');
    }
    public function Topics()
    {
        return $this->hasMany('App\MeetupsTopics')->get();
    }

    protected $table = 'meetups';

}
