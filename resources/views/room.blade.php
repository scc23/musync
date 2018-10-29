@extends('layouts.app')

@section('title', 'Room')

@section ('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-12">
            <div class="card">
                <div class="card-header">{{ $room->name }}: {{ $room->id }}</div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-4">
                            <search-component access-token="{{ Auth::user()->api_token }}"></search-component>
                        </div>
                        <div class="col-4">
                            <spotify-player-component csrf-token="{{csrf_token()}}" access-token="{{Auth::user()->token}}"></spotify-player-component>
                            <playlist-component csrf-token="{{csrf_token()}}"></playlist-component>
                        </div>                        

                        <div class="col-4"> 
                            {{-- User list component--}}
                            <user-list-component :users="{{$users}}" csrf-token="{{csrf_token()}}"></user-list-component>
                            {{-- Chat components --}}
                            <div class="card">
                                <div class="card-header">Chat</div>
                                <div class="card-body">  
                                    <chat-messages-component csrf-token="{{csrf_token()}}"></chat-messages-component>                       
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
@endsection
