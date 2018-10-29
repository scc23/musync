<?php

namespace App\Events;

use App\User;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class SpotifyPlayerAction implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * User that sent the request
     * 
     * @var User
     */
    public $user;

    /**
     * Request details
     * 
     * @var request
     */
    public $request;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(User $user, String $request)
    {
        $this->user = $user;
        $this->request = $request;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        // Todo: This channel needs to match the room channel
        return new Channel('test-event');
    }
}
