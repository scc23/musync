<?php

namespace App\Http\Controllers;

use App\User;
use App\Room;
use App\RoomHelper;
use App\RoomMembership;
use App\Message;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class RoomController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware(['auth', 'auth.room']);
    }

    /**
     * Handle request (GET /room/{id}) for displaying a Room.
     *
     * @param  Illuminate\Http\Request  $request
     */
    public function show(Request $request) {
        $room_id = $request->route('id');
        $room = Room::find($room_id);

        return view('room', ['room' => $room]);
    }
}
