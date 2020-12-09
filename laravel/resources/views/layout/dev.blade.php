<html>
  <head>
    <title>
      @yield('title')
      @ timesink
    </title>
     <link rel="shortcut icon" href="/logo.png" type="image/x-icon">
    <link href="/css/main.css" rel="stylesheet" type="text/css" />
    <link href="/css/dev.css" rel="stylesheet" type="text/css" />
    <link href="https://fonts.googleapis.com/css2?family=Roboto&family=Rubik+Mono+One&display=swap" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Roboto+Mono:wght@200&display=swap" rel="stylesheet">
    <script
			 src="https://code.jquery.com/jquery-3.5.1.js"
			 integrity="sha256-QWo7LDvxbWT2tbbQ97B53yJnYU3WhH/C8ycbRAkjPDc="
			 crossorigin="anonymous"></script>
       <script src="https://kit.fontawesome.com/1bc8ccb35e.js" crossorigin="anonymous"></script>
  </head>
  <body>
    @include('includes.header')
    <div class='content'>
      @include('dev.parts.log')
      @yield('content')
    </div>
    @if (session('popup'))
    <div class='popup' id='popupmessage'>
      <div class='window' >
        <div class='heading'>
          <span>{{session('popup_title') ?? 'Message'}}</span>
        </div>
        <div class='main form middle'>
          <p>{{ session('popup') }}</p>
          <button>OK</button>
        </div>
      </div>
    </div>
      <script>
        $('#popupmessage').click(()=>{
          $('#popupmessage').hide();
        });
      </script>
    @endif
    <div class='footer'>
    </div>
  </body>
  <script>
    $('#openauth').click(()=>{
      $('#authpopup').fadeIn(100);
    });
    $('#authpopup').click(()=> {
      $('#authpopup').hide();
    });
    $('#popupwindow').click(function(e) {
      e.stopPropagation();
    });
  </script>
  <footer>

  </footer>
</html>
