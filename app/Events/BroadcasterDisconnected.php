<?php

namespace App\Events;

use App\User;
use App\Room;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class BroadcasterDisconnected implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $room;

    public $user;

    /**
     * @param Room $room
     * @param User $user
     *
     * @return void
     */
    public function __construct(Room $room, User $user)
    {
        $this->room = $room;
        $this->user = $user;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('room.'.$this->room->id);
    }

    /**
     * Get the data to broadcast.
     *
     * @return array
     */
    public function broadcastWith()
    {
        return [
            'room' => [ 
                'id' => $this->room->id,
            ],

            'user' => [
                'id' => $this->user->id,
                'name' => $this->user->name,
            ]
        ];
    }
}
