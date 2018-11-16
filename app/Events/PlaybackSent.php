<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class PlaybackSent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $room_id;

    public $track_uri;

    public $track_position;

    public $is_paused;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($room_id, $track_uri, $track_position, $is_paused)
    {
        $this->room_id = $room_id;
        $this->$track_uri = $track_uri;
        $this->$track_position = $track_position;
        $this->is_paused = $is_paused;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('room.'.$this->room_id);
    }
}
