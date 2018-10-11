@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-12">
            <div class="card">
                <div class="card-header">Room Name: {{ $room_id }}</div>
                <div class="card-body">
                  <div class="row">
                    <div class="col-3">TODO: Search Song</div>
                    <div class="col-6">TODO: Song Playlist</div>
                    <div class="col-3">TODO: User List</div>
                  </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
