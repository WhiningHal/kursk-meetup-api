<?php
use App\People;
use App\Meetup;
use App\Topic;
use App\Content;
use App\MeetupsTopics;
/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/', function () {
    return view('welcome');
});
Route::get('/meetups', function() {
    if (isset($_GET['ofs'])) { $ofs=$_GET['ofs']; } else { $ofs = 0; }
    if (isset($_GET['q']))   { $q=$_GET['q']; } else { $q = 5; }

    $meetups = Meetup::orderBy('meetupdatetime','asc')->skip($ofs)->take($q)->get();
    $marray = array();
    foreach ($meetups as $m)
    {
        $topics=array();
        foreach($m->Topics() as $t)
        {
            $content = array();
            foreach($t->Topic()->Contents() as $c)
            {
                $content[]=["url" => $c->url(), "title" => $c->title()];
            }
            $topics[]=["auname" => $t->Person()->name,
                       "title" => $t->Topic()->title,
                       "content" => $content];
        }
        $marray[] = ["datetime" => $m->meetupdatetime,
                     "place" => $m->Place()->name,
                     "lgt" => $m->Place()->longitude,
                     "lat" => $m->Place()->latitude,
                     "note" => $m->note,
                     "topics" => $topics ];

    }
    return response()->json($marray,200,['Content-type'=> 'application/json; charset=utf-8'],JSON_UNESCAPED_UNICODE);
});
Route::get('/members', function() {
      $members = People::orderBy('id','asc')->get();
      $marray=array();
      foreach ($members as $m)
      {
          $marray[] = ["name"=>$m->name,"vk"=>$m->vk,"github"=>$m->github];
      }
      return response()->json($marray,200,['Content-type'=> 'application/json; charset=utf-8'],JSON_UNESCAPED_UNICODE);
});
