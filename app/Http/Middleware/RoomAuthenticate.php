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
        $room = Room::find($room_id);

        if (!$room) {
            return abort(Response::HTTP_NOT_FOUND);
        }

        if (!$request->route()->named('room.membership.create')) {
            if (!RoomHelper::isMember($room)) {
                $password = Input::get('join-room-password');

                $membership = RoomHelper::createMembership($room, $password);
                if (!$membership) {
                    return response('User is not authorized to access this room',
                        Response::HTTP_UNAUTHORIZED);
                }
            }
        }

        return $next($request);
    }
}
