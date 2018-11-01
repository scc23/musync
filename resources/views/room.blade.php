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
                            <playlist-component csrf-token="{{csrf_token()}}"></playlist-component>
                        </div>
                        <div class="col-4">
                            <user-list-component csrf-token="{{csrf_token()}}"></user-list-component>
                            <chat-messages csrf-token="{{csrf_token()}}"></chat-messages>
                            <chat-form-component access-token="{{ Auth::user()->token }}"></chat-form-component>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
