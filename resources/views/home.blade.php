@extends('layouts.app')

@section('title', 'Home')

@section('content')
<div id="home-app">
    <home-component
        csrf-token="{{csrf_token()}}"
        access-token="{{Auth::user()->api_token}}">
    </home-component>
</div>
@endsection
