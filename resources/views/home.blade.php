@extends('layouts.app')
@section('title', 'Home')
@section('content')

<!--  -->
<link href="{{ asset('css/home.css') }}" rel="stylesheet" type="text/css">
<!--  -->


<script src="{{ asset('js/home.js') }}" defer></script>
<div id="home-app">
    <home-component
        user-id="{{Auth::id()}}"
        csrf-token="{{csrf_token()}}"
        access-token="{{Auth::user()->api_token}}">
    </home-component>
</div>
@endsection