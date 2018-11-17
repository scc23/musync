<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class JoinedRoom implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $room_id;

    public $user_id;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($room_id, $user_id)
    {
        $this->room_id = $room_id;
        $this->user_id = $user_id;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('home.'.$this->user_id);
    }

    /**
    * Get the data to broadcast.
    *
    * @return array
    */
   public function broadcastWith()
   {
       return [
           'roomId' => $this->room_id
       ];
   }

}
