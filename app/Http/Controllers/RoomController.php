<?php

namespace App\Http\Controllers;

use App\Room;
use App\RoomHelper;
use App\RoomMembership;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class RoomController extends Controller
{
    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => 'required|string|unique:rooms',
            'password' => 'required_unless:password,""|string',
        ]);
    }

    /**
     * Create a new room instance after validated.
     *
     * @param  array  $data
     * @return \App\Room
     */
    protected function create(array $data) {
        $id = str_random(4);
        # Generate new ID if found.
        while (Room::find($id) != null) {
          $id = str_random(4);
        }

        return Room::create([
            'id' => $id,
            'name' => $data['name'],
            'password' => (empty($data['password']) ? '' : Hash::make($data['password']))
        ]);
    }

    /**
     * Handle request (POST /rooms) for registering a new Room.
     *
     * @param  Illuminate\Http\Request  $request
     */
    public function register(Request $request)
    {
        $data = [];
        $data['name'] = $request->input('create-room-name');
        $password = $request->input('create-room-password');
        $data['password'] = $password ? $password : '';
        $this->validator($data)->validate();

        $room = $this->create($data);

        return redirect()->route('room.join', ['id' => $room->id])
                         ->with('password', $password);
    }

    /**
     * Handle request (GET /room/{id}} for displaying a Room.
     *
     * @param  Illuminate\Http\Request  $request
     */
    public function show(Request $request) {
        $room_id = $request->route('id');
        $room = Room::find($room_id);

        if (!$room) {
            return abort(Response::HTTP_NOT_FOUND);
        }

        if ($room->password && !RoomHelper::isMember($room)) {
            return response('User is not authorized to access this room',
                            Response::HTTP_UNAUTHORIZED);
        }

        return view('room', ['room' => $room]);
    }
}
