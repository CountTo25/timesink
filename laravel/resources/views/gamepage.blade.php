@extends('layout.front')
@section('title', $game->name)
@section('content')
  @include('parts.apiwrapper')
  @include('parts.game')
@endsection
