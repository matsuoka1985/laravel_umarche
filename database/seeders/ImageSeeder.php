<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;


class ImageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('images')->insert([
            [
                'owner_id'=>1,
                'filename'=>'sample_1.jpg',
                'title'=>null
            ],
            [
                'owner_id'=>1,
                'filename'=>'sample_2.jpg',
                'title'=>null
            ],
            [
                'owner_id'=>1,
                'filename'=>'sample_3.jpg',
                'title'=>null
            ],
            [
                'owner_id'=>1,
                'filename'=>'sample_4.jpg',
                'title'=>null
            ],
            [
                'owner_id'=>1,
                'filename'=>'sample_5.jpg',
                'title'=>null
            ],
            [
                'owner_id'=>1,
                'filename'=>'sample_6.jpg',
                'title'=>null
            ],
        ]);
    }
}
