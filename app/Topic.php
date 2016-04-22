<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Topic extends Model
{
    protected $table = 'topics';
    protected $guarded = ['id'];

    public function Contents()
    {
        return $this->hasMany('App\Content')->get();
    }
    public function Meetups()
    {
        return $this->hasMany('App\MeetupsTopics')->get();
    }
}
