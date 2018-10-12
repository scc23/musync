<?php

namespace App\Http\Controllers;

use App\Track;
use Illuminate\Http\Request;
use SpotifyWebAPI;

class TrackController extends Controller
{
	/*
      Creates a new Track with a randomly generated
      4 character ID.
    */
	public function create($title, $artist, $album, $room_id)
	{
		id = str_random(4);
	    # Generate new ID if found.
	    while (Track::find($id) != null) {
	    	$id = str_random(4);
	    }

	    $data = [
	    	'id' => $id,
			'title' => $title,
			'artist' => $artist,
			'album' => $album,
			'group_room_id' => $room_id
	    ];

	    $track = Track::create($data);

	    # TODO: Redirect to group room page.
	    return redirect()->route('home');
	}
}
