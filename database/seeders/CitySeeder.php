<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //读取城市数据集
        DB::unprepared(file_get_contents(__DIR__ . '/sql/city.sql'));

        // 建立缓存

    }
}
