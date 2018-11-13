@extends('layouts.app')
@section('title', 'Room')
@section ('content')



<!--  -->
<link href="{{ asset('css/room.css') }}" rel="stylesheet" type="text/css">
<!--  -->


<script src="https://sdk.scdn.co/spotify-player.js" defer></script>
<script src="{{ asset('js/room.js') }}" defer></script>

<div id="room-app">
    <room-component user-name="{{Auth::user()->name}}"
                    user-id="{{Auth::user()->id}}"
                    room-name="{{$room->name}}"
                    room-id="{{$room->id}}"
                    csrf-token="{{csrf_token()}}"
                    access-token="{{Auth::user()->api_token}}"
                    spotify-id="{{Auth::user()->spotify_id}}">
    </room-component>
</div>
@endsection


