<div class='header'>
  <a href='/'>
    <div class='logo'>
    </div>
  </a>
  <div class='nav'>
    <span class='navnode'>All games</span>
    <span class='navnode'>New</span>
    <span class='navnode'>Hot</span>
    <span class='navnode'>Recently updated</span>
    <div class='find navnode'>
      <input placeholder="Search for...">
    </div>
  </div>
  <div class='auth'>
    @if (!Auth::check())
    <span class='clickable' id='openauth'>Log in</span>
    @else
    <span class='navnode'>Logged in as {{Auth::user()->name}}</span>
    <span class='navnode'><a href='/dev'>/dev</a></span>
    @endif
  </div>
</div>
<div class='auth popup' id='authpopup' style='display: none'>
  <div class='window'>
    <div class='heading'>
      <span>Welcome</span>
    </div>
    @include('includes.authform')
  </div>
</div>
