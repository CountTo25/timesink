@extends('layout.front')
@section('title', $game->shortlink)
@section('content')
  @include('parts.apiwrapper')
  @include('parts.game')
@endsection
