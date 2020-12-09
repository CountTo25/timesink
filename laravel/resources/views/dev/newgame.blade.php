@extends('layout.dev')
@section('title', 'Upload new game')
@section('content')
  <div class='updateholder devblock'>
    <div class='updateheader'>
      <span>New game</span>
    </div>
    <div class='toggleupdatemode'>
      <input id='push_git' value='git' name='pushvia' type='radio' checked autocomplete="off">
      <label for='push_git'>via github <i class="fab fa-github"></i></label>
      <input id='push_zip' value='zip' name='pushvia' type='radio'>
      <label for='push_zip'>via .zip <i class="fas fa-file-archive"></i></label>
    </div>
    <div class='formwrap'>
    <div class='formcontainer switch_push' form-for='git'>
      <form class='gameupdateform' action="/dev/upload/newfromgit" method="post"  enctype="multipart/form-data">
        @csrf
        <label>Game display name</label>
        <input name='game_name' placeholder='Game Name' required></input>
        <label>Game shortlink</label>
        <input name='shortlink' placeholder='shortlink' required></input>
        <label for='html'>github username</label>
        <input name='username' placeholder='countto25' required>
        <label for='test'>github repo name</label>
        <input name='repo' placeholder='overflow' required>
        <input class='btn' type='submit' value='Post game'>
      </form>
    </div>
    <div class='formcontainer switch_push' form-for='zip' style='display: none'>
      <form class='gameupdateform' action="/dev/upload/new" method="post"  enctype="multipart/form-data">
        @csrf
        <label>Game display name</label>
        <input name='game_name' placeholder='Game Name' required></input>
        <label>Game shortlink</label>
        <input name='shortlink' placeholder='shortlink' required></input>
        <input class='fileinput' type="file" id='patchzip' name="package" accept=".zip" required>
        <label for="patchzip"><i class="fas fa-upload"></i> <span>Select .zip file</span></label>
        <input class='btn' type='submit' value='Post game'>
      </form>
    </div>
  </div>
  </div>
  <script>
    var inputs = document.querySelectorAll('.fileinput');
    Array.prototype.forEach.call(inputs, function(input){
    var label	 = input.nextElementSibling,
        labelVal = label.innerHTML;
    input.addEventListener('change', function(e){
      var fileName = '';
      if( this.files && this.files.length > 1 )
        fileName = ( this.getAttribute( 'data-multiple-caption' ) || '' ).replace( '{count}', this.files.length );
        else
        fileName = e.target.value.split( "\\" ).pop();
        if( fileName )
        label.querySelector( 'span' ).innerHTML = fileName;
        else
        label.innerHTML = labelVal;
      });
  });
  </script>
  <script>
    $(document).ready(()=>{
      $('input[name=pushvia]').change(function() {
          $('.switch_push').hide();
          $('.switch_push[form-for="'+$(this).val()+'"]').show();
      });
    })
  </script>
@endsection
