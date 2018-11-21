@extends('layouts.app')
@section('title', 'Home')
@section('content')


<script src="{{ asset('js/home.js') }}" defer></script>
<div id="home-app">
    <home-component
        user-id="{{Auth::id()}}"
        access-token="{{Auth::user()->api_token}}">
    </home-component>
</div>
@endsection