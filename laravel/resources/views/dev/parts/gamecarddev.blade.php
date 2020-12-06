<?php
if (!isset($g)) return 'kek';
?>

<a href='/dev/game/{{$g->id}}'>
<div class='gamecard'>
  <div class='gameimg'></div>
  <span class='title'>{{$g->name}}<span>
</div>
</a>
