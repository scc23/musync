<?php

namespace App\Http\Controllers;

use App\Events\MessageSent;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class RoomMessageAPIController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware(['auth:api', 'auth.room']);
    }

    /**
     * Send the provided message contained within the request body to the
     * respective room channel.
     */
    public function sendMessage(Request $request)
    {
        $room_id = $request->route('id');

        $body = $request->json()->all();
        $message = isset($body['message']) ? $body['message'] : '';

        if (empty($message)) {
            $error = "Invalid messsage, message must not be empty.";
            return response()->json(["messageError" => $error], Response::HTTP_BAD_REQUEST);
        }

        $username = Auth::user()->name;
        broadcast(new MessageSent($room_id, $username, $message));

        return response()->json([
            'username' => $username,
            'message' => $message
        ], Response::HTTP_OK);
    }
}
