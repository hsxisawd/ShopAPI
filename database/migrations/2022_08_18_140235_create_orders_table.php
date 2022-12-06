<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id')->comment('下单的用户');
            $table->string('order_num')->comment('订单单号');
            $table->integer('amount')->comment('总金额 单位分');
            $table->tinyInteger('status')->default(1)->comment('订单状态：1下单 2支付 3发货 4收货 5失效');
            $table->integer('address_id')->comment('收货地址id');
            $table->string('express_type')->comment('快递类型：SF YT YD')->nullable();
            $table->string('express_num')->comment('快递单号')->nullable();
            $table->timestamp('pay_time')->comment('支付时间')->nullable();
            $table->string('pay_type')->comment('支付类型 1为支付宝 2为微信支付')->nullable();
            $table->string('trade_num')->comment('支付单号')->nullable();
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
        Schema::dropIfExists('orders');
    }
}
