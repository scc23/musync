@extends('layouts.app')

@section('title', 'Room')

@section ('content')
<<<<<<< HEAD
<script src="https://sdk.scdn.co/spotify-player.js" defer></script>
<script src="{{ asset('js/room.js') }}" defer></script>

<div id="room-app">
    <room-component room-name="{{$room->name}}"
                    room-id="{{$room->id}}"
                    csrf-token="{{csrf_token()}}"
                    access-token="{{Auth::user()->api_token}}"
                    spotify-id="{{Auth::user()->spotify_id}}">
    </room-component>
=======

<script src="{{ asset('js/room.js') }}" defer></script>

<div id="room-app">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">{{ $room->name }}: {{ $room->id }}</div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-4">
                                <search-component access-token="{{ Auth::user()->api_token }}" spotify-id="{{ Auth::user()->spotify_id }}"></search-component>
                            </div>
                            <div class ="col-4">
                                <spotify-player-component csrf-token="{{csrf_token()}}" access-token="{{Auth::user()->api_token}}" spotify-id="{{ Auth::user()->spotify_id }}"></spotify-player-component>
                                <playlist-component csrf-token="{{csrf_token()}}"></playlist-component>
                            </div>
                            <div class="col-4"> 
                                {{-- User list component--}}
                                <user-list-component :users="{{$users}}" csrf-token="{{csrf_token()}}"></user-list-component>
                                {{-- Chat components --}}
                                <div class="card">
                                    <div class="card-header">Chat</div>
                                    <div class="card-body">  
                                        <chat-messages-component :messages="{{$messages}}" csrf-token="{{csrf_token()}}"></chat-messages-component>              
                                    </div>
                                    <div class="card-footer">
                                        <chat-form-component access-token="{{ Auth::user()->token }}"></chat-form-component>
                                    </div>
                                </div>
                                {{-- End Chat components --}}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
>>>>>>> Add Message model, database retrieval, user to many messages
</div>
@endsection
