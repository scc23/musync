@extends('layouts.app')

@section('content')
<home-component csrf-token="{{csrf_token()}}"></home-component>
@endsection
