<div class='main form'>
  <form action="/auth" method="post">
    @csrf
    <input name='email'></input>
    <input type='password' name='password'></input>
    <input type="submit" value='Log in'></input>
  </form>
</div>
