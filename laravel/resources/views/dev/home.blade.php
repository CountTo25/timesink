@extends('layout.dev')
@section('title', 'Home')
@section('content')
  <div class='dev home newgame devblock'>
    Maybe it is time to <a href='{{URL::to("/")}}/dev/upload/new'>submit new game</a> ?
  </div>
  <div class='devblock dsubtitle'>Your games</div>
  @include('dev.parts.mygames')
@endsection
