@extends('layout.dev')
@section('title', 'Home')
@section('content')
  <div>Hello, {{Auth::user()->name}}</div>
  <div>Your games: </div>
  @include('dev.parts.mygames')
@endsection
