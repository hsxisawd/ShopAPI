<?php

namespace App\Listeners;

use App\Events\OrderPost;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;

class SendEmailToOrderUser
{


    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {

    }

    /**
     * Handle the event.
     *
     * @param  \App\Events\OrderPost  $event
     * @return void
     */
    public function handle(OrderPost $event)
    {
        $event->orders->express_type=$event->express_type;
        $event->orders->express_num=$event->express_num;
        $event->orders->status=3;
        $event->orders->save();

        Mail::to($event->orders->user)->queue(new \App\Mail\OrderPost($event->orders));
    }
}
