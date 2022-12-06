<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //创建用户
        $user=User::create([
            'username'=>'superAdmin',
            'email'=>'super@a.com',
            'email_status'=>0,
            'password'=>bcrypt('superAdmin'),
            'phone'=>12345678910,
            'phone_status'=>0,
            'nickname'=>'超级管理员',
        ]);

        //给用户分配权限
        $user->assignRole('super-admin');
    }
}
