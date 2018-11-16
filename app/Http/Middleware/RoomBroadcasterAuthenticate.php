<?php

namespace App\Http\Middleware;

use App\Room;
use Auth;
use Closure;
use Illuminate\Http\Response;

class RoomBroadcasterAuthenticate
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $room_id = $request->route('id');
        $room = Room::find($room_id);
        $broadcaster = $room->broadcaster;

        if (empty($broadcaster) || $broadcaster->user_id != Auth::id()) {
            return response('User is not currently the broadcaster for this room.',
                Response::HTTP_UNAUTHORIZED);
        }

        return $next($request);
    }
}
