@extends('layouts.app')

@section('title', 'Room')

@section('content')
<div id="room-app">
    <room-component room-name="{{$room->name}}"
                    room-id="{{$room->id}}"
                    csrf-token="{{csrf_token()}}"
                    access-token="{{Auth::user()->api_token}}"
                    spotify-id="{{Auth::user()->spotify_id}}">
    </room-component>
</div>
@endsection
