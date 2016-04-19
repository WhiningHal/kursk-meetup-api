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
        DB::delete('delete from meetups_topics');
        DB::delete('delete from content');
        DB::delete('delete from topics');
        DB::delete('delete from people');
        DB::delete('delete from meetups');
        DB::delete('delete from places');
        $obj_people = json_decode(file_get_contents('/var/www/database/seeds/members.txt'),true);
        foreach($obj_people as $k => $v)
        {
            DB::table('people')->insert(['name'=>$v['name'],'vk'=>$v['vk'],'github'=>$v['github']]);
        }

        $obj_meetups = json_decode(file_get_contents('/var/www/database/seeds/meetups.txt'),true);
        foreach($obj_meetups as $k => $v)
        {
            $placeId = DB::table('places')->select('id')->where('name','=',$v['place'])->get();
            if (count($placeId)==0)
            {
                DB::table('places')->insert(['name'=>$v['place'],'longitude'=>strval($v['lgt']),'latitude'=>strval($v['lat'])]);
                $placeId = DB::table('places')->select('id')->where('name','=',$v['place'])->get();
            }
            $placeId = $placeId[0]->{'id'};
            DB::table('meetups')->insert(['datetime'=>strval($v['datetime']), 'place_id'=>strval($placeId), 'note'=>$v['note']]);
            foreach($v['topics'] as $nv)
            {
                DB::table('topics')->insert(['title'=>$nv['title']]);
                $topicId = DB::table('topics')->select('id')->where('title','=',$nv['title'])->get();
                $topicId = $topicId[0]->{'id'};
                $ordNr = 1;
                if (array_key_exists('content',$nv))
                {
                    foreach($nv['content'] as $cv)
                    {
                        DB::table('content')->insert(['topic_id'=>strval($topicId), 'ordinal_nr'=>strval($ordNr), 'url'=>$cv['url'],'title'=>$cv['title']]);
                        $ordNr = $ordNr + 1;
                    }
                }
                $authorId = DB::table('people')->select('id')->where('name','=',$nv['auname'])->get();
                $authorId = $authorId[0]->{'id'};
                DB::table('meetups_topics')->insert(['meetup_date'=>$v['datetime'],'topic_id'=>$topicId,'person_id'=>$authorId]);
            }
        }
    }
}
