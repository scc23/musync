@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-12">
            <div class="card">
                <div class="card-header">{{ $room->name }}: {{ $room->id }}</div>
                <div class="card-body">
                    <div class="row">
                        <search-component access-token="{{ Auth::user()->token }}"></search-component>
                        <playlist-component csrf-token="{{csrf_token()}}"></playlist-component>
                        <user-list-component csrf-token="{{csrf_token()}}"></user-list-component>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
