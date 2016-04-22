<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Content extends Model
{
    protected $table = 'content';
    protected $primaryKey = ['topic_id','ordinal_nr'];
    public function Topic()
    {
        return Topic::find($this->attributes['topic_id']);
    }
    public function url()
    {
        return $this->attributes['url'];
    }
    public function title()
    {
        return $this->attributes['title'];
    }
}
