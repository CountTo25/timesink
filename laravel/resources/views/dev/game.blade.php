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
    <!-- rolldown later, plain now -->
    <!-- dont forget to create upload guidelines later -->
    <div class='updateholder'>
      <div class='updateheader'>
        <span>New update</span>
      </div>
      <div class='toggleupdatemode'>
        <input id='push_git' value='git' name='pushvia' type='radio' checked autocomplete="off">
        <label for='push_git'>via github <i class="fab fa-github"></i></label>
        <input id='push_zip' value='zip' name='pushvia' type='radio'>
        <label for='push_zip'>via .zip <i class="fas fa-file-archive"></i></label>
      </div>
      <div class='formwrap'>
      <div class='formcontainer switch_push' form-for='git'>
        <form class='gameupdateform' action="/dev/game/{{$g->id}}/snatchgit" method="post"  enctype="multipart/form-data">
          @csrf
          <label for='html'>github username</label>
          <input name='username' placeholder='countto25' required>
          <label for='test'>github repo name</label>
          <input name='repo' placeholder='overflow' required>
          <input class='btn' type='submit' value='push an update'>
        </form>
      </div>
      <div class='formcontainer switch_push' form-for='zip' style='display: none'>
        <form class='gameupdateform' action="/dev/game/{{$g->id}}/update" method="post"  enctype="multipart/form-data">
          @csrf
          <input class='fileinput' type="file" id='patchzip' name="package" accept=".zip" required>
          <label for="patchzip"><i class="fas fa-upload"></i> <span>Select .zip file</span></label>
          <input class='btn' type='submit' value='push an update'>
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
  </div>
  </div>
@endsection
