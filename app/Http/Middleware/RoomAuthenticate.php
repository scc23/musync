<?php

namespace App\Http\Middleware;

use App\Room;
use App\RoomHelper;
use Closure;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Input;

class RoomAuthenticate
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
        if (!$room_id) {
            return abort(Response::HTTP_BAD_REQUEST);
        }

        $room = Room::find($room_id);
        if (!$room) {
            return abort(Response::HTTP_NOT_FOUND);
        }

        if (!RoomHelper::isMember($room) && !RoomHelper::createMembership($room)) {
            return response('User is not authorized to access this room',
                Response::HTTP_UNAUTHORIZED);
        }

        return $next($request);
    }
}
