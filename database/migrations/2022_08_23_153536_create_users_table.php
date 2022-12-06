<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('username')->unique()->nullable();
            $table->string('email')->unique()->nullable();
            $table->tinyInteger('email_status')->default(1)->comment('邮箱验证状态，0是正常，1为未验证');
            $table->string('password')->nullable();
            $table->bigInteger('phone')->unique()->nullable();
            $table->tinyInteger('phone_status')->default(1)->comment('手机号验证状态，0是正常，1为未验证');
            $table->string('wx_openid')->nullable();
            $table->string('wx_unionid')->nullable();
            $table->json('wx_token')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
