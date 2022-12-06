<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class OrderPost
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $orders;
    public $express_type;
    public $express_num;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($orders,$express_type,$express_num)
    {
        $this->orders=$orders;
        $this->express_type=$express_type;
        $this->express_num=$express_num;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('channel-name');
    }
}
