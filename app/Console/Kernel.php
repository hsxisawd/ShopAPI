<?php

namespace App\Console;

use App\Models\Orders;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // 定时检测订单状态，超过10分钟的未支付的自动作废
        // 真实的项目会使用长连接
        $schedule->call(function (){
            $orders=Orders::where('status',1)
                ->where('created_at','<',date('Y-m-d H:i:s',time()-600))
                ->with('orderDetails.goods')
                ->get();
            //循环订单，修改订单状态，还商品库存
            try{
                DB::beginTransaction();
                foreach ($orders as $order){
                    $order->status=5;
                    $order->save();
                    //还原库存
                    foreach ($order->orderDetails as $details){
                        $details->goods->increment('stock',$details->num);
                    }
                };
                DB::commit();

            }catch (\Exception  $e){
                DB::rollBack();
                Log::error($e);
            }
        })->everyMinute();
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
