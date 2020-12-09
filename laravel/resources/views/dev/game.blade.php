@extends('layout.dev')
@section('title', '/dev/'.$g->shortlink)
@section('content')
  <div class='dev h'>
    <span class='dtitle'>{{$g->name}}</span>
    <a class='nostyle' href="{{URL::to('/')}}/play/{{$g->shortlink}}">{{URL::to('/')}}/play/{{$g->shortlink}}</span></a>

  </div>
  <div class='dev generalsettings'>
    <span class='dsubtitle'>Settings</span></br>
    <span class='block'>Preview picture</span></br>
    <div class='settingsblock'>
      <form class='gameupdateform' action="/dev/game/{{$g->id}}/img" method="post"  enctype="multipart/form-data">
        @csrf
        <input class='inputfile' type='file' name='bannerpic' accept='image/jpeg,image/png'>
        <input class='btn' type='submit' value='Update'>
      </form>
    </div>

  </div>
  <div class='dev gameupdate'>
    <span class='dsubtitle'>New update</span></br>
    <!-- rolldown later, plain now -->
    <!-- dont forget to create upload guidelines later -->
    <div class='settingsblock'>
      <form class='gameupdateform' action="/dev/game/{{$g->id}}/update" method="post"  enctype="multipart/form-data">
        @csrf
        <label for='html'>.zip with game</label>
        <input type="file" name="package" accept=".zip" required>
        <input class='btn' type='submit' value='push an update'>
      </form>
    </div>
    <span class='dsubtitle'>Update from git</span></br>
    <div class='settingsblock'>
      <form class='gameupdateform' action="/dev/game/{{$g->id}}/snatchgit" method="post"  enctype="multipart/form-data">
        @csrf
        <label for='html'>github username</label>
        <input name='username'>
        <label for='test'>github repo name</label>
        <input name='repo'>
        <input class='btn' type='submit' value='push an update'>
      </form>
    </div>
  </div>
  </div>
@endsection
