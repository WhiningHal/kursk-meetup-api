<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // $this->call(UsersTableSeeder::class);
        $obj_people = json_decode(file_get_contents('members.txt'),true);
        foreach($obj_people as $k => $v)
        {
            # print_r($v);
            DB::table('people')->insertIgnore(['name'=>$v['name'],'vk'=>$v['vk'],'github'=>$v['github']]);
        }

        $obj_meetups = json_decode(file_get_contents('meetups.txt'),true);
        foreach($obj_meetups as $k => $v)
        {
            # print_r($v);
            DB::table('places')->insertIgnore(['name'=>$v['place'],'longitude'=>$v['lng'],'latitude'=>$v['lat']]);
            $placeId = DB::table('places')->select('id')->where('name','=',$v['place'])->get();
            DB::table('meetups')->insertIgnore(['datetime'=>$v['datetime'], 'place_id'=>$placeId, 'note'=>$v['note']]);
            foreach($v['topics'] as $nv)
            {
                DB::table('topics')->insertIgnore(['title'=>$nv['title']]);
                $topicId = DB::table('topics')->select('id')->where('title','=',$nv['title'])->get();
                $ordNr = 1;
                foreach($nv['content'] as $cv)
                {
                    DB::table('content')->insertIgnore(['topic_id'=>$topicId, 'ordinal_nr'=>$ordNr, 'url'=>$cv['url'],'title'=>$cv['title']]);
                    $ordNr = $ordNr + 1;
                }
                $authorId = DB::table('people')->select('id')->where('name','=',$nv['auname'])->get();
                DB::table('meetups_topics')->insertIgnore(['meetup_date'=>$v['datetime'],'topic_id'=>$topicId,'person_id'=>$authorId]);
            }
        }
    }
}
