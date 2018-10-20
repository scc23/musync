<?php

namespace App\Http\Controllers;

use App\Room;
use Illuminate\Http\Request;
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
     * Handle request for registering a new Room.
     *
     * @param  Illuminate\Http\Request  $request
    */
    public function register(Request $request)
    {
        $data = [];
        $data['name'] = $name = $request->input('create-room-name');
        $password = $request->input('create-room-password');
        $data['password'] = (empty($password) ? '' : Hash::make($password));
        $this->validator($data)->validate();

        $room = $this->create($data);

        return redirect()->route('room.id', ['id' => $room->id]);
    }
}
