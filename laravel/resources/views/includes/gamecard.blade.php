<?php
if (!isset($g)) return null;
?>

<a href='/play/{{$g->shortlink}}'><div class='gamecard'>
  <div class='gameimg'></div>
  <span class='title'>{{$g->name}}<span>
</div></a>
