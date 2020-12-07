@extends('layout.front')
@section('title', 'Home')
@section('content')
<div>
  <p>Join us!</p>
  <p>Create an account and get access to nice stuff. Cloudsaves and stuff, yeah. A lot of stuff (nope)</p>
  <form action="/signup" method="post">
    @csrf
    <input name='email' placeholder='granma@timeink.space'>
    <input name='name' placeholder='5 hours till update'>
    <input name='password' type='password'>
    <input type='submit' value='Create an account'>
  </form>
</div>
@endsection
