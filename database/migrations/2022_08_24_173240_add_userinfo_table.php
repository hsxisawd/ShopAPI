<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddUserinfoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('nickname')->default('new_user')->comment('昵称');
            $table->string('headimg')->default('default_headimg.jpg')->comment('头像');
            $table->tinyInteger('sex')->default('2')->comment('性别：0男 1女 2未知');
            $table->string('Personal_Introduction')->comment('个人介绍')->nullable();
            $table->tinyInteger('is_locked')->default(0)->after('email')->comment('用户禁用状态，0正常 1禁用');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            //
        });
    }
}
