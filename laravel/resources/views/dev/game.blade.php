@extends('layout.dev')
@section('title', 'Home')
@section('content')
  <div><span>{{$g->name}}</span></div>
  <div class='dev gameupdate'>
    <p>Ready for a new update?</p>
    <!-- rolldown later, plain now -->
    <p>Same rules apply. JS, HTML and CSS are only types allowed in for now. More file types support (Unity HTML5 and others) will come later</p>
    <!-- dont forget to create upload guidelines later -->
    <form class='gameupdateform' action="/dev/game/{{$g->id}}/update" method="post"  enctype="multipart/form-data">
      @csrf
      <label for='html'>index.html</label>
      <input type="file" name="html" accept="text/html" required>
      <label for='js'>.js files</label>
      <input type="file" name="js[]" accept="application/javascript" multiple>
      <label for='css'>.css files</label>
      <input type="file" name="css[]" accept="text/css" multiple>
      <input type='submit' value='push an update'>
    </form>
  </div>
@endsection
