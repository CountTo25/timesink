<div class='main form'>
  <form action="/auth" method="post">
    @csrf
    <label>Email</label>
    <input name='email' placeholder='granma@cookie.click'></input>
    <label>Password</label>
    <input type='password' name='password'></input>
    </br>
    <input type="submit" value='Log in'></input>
  </form>
  <div class='form helper'>
    <div class='left'>
      <a href='/signup'>Create an account</a>
    </div>
    <div class='right'>
      <a href='/recover'>Reset password</a>
    </div>
  </div>
</div>
