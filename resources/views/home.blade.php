@extends('layouts.app')

@section('title', 'Home')

@section('content')
<home-component csrf-token="{{csrf_token()}}" access-token="{{Auth::user()->api_token}}"></home-component>
@endsection
