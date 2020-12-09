@extends('layout.dev')
@section('title', 'Home')
@section('content')
  <div><span>Upload new game</span></div>
  <div class='dev gameupdate'>
    <p>Share new game</p>
    <!-- rolldown later, plain now -->
    <p>Upload a .zip package of your game</p>
    <p>TODO: get from github releases</p>
    <!-- dont forget to create upload guidelines later -->
    @if (session('error'))
      <div class='uploaderror'><p > {{ session('error') }}</p></div>
    @endif
    <form class='gameupdateform' action="/dev/upload/new" method="post"  enctype="multipart/form-data">
      @csrf
      <input name='game_name' placeholder='Game name' required></input>
      <input name='shortlink' placeholder='Shortlink' required></input>
      <label for='package'>game.zip</label>
      <input type="file" name="package" accept=".zip" required>
      <input type='submit' value='submit game'>
    </form>
  </div>
@endsection
