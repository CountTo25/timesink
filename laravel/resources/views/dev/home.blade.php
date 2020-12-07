@extends('layout.front')
@section('title', 'Home')
@section('content')
  <div>Hello, {{Auth::user()->name}}</div>
  <div>Maybe it is time to <a href='{{URL::to("/")}}/dev/upload/new'>submit new game?</a></div>
  <div>Your games: </div>
  @include('dev.parts.mygames')
@endsection
