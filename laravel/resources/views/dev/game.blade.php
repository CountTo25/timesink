@extends('layout.front')
@section('title', 'Home')
@section('content')
  <div><span>{{$g->name}}</span></div>
  <div class='dev gameupdate'>
    <p>Ready for a new update?</p>
    <!-- rolldown later, plain now -->
    <!-- dont forget to create upload guidelines later -->
    <form class='gameupdateform' action="/dev/game/{{$g->id}}/update" method="post"  enctype="multipart/form-data">
      @csrf
      <label for='html'>game.zip</label>
      <input type="file" name="package" accept=".zip" required>
      <input type='submit' value='push an update'>
    </form>
  </div>
@endsection
