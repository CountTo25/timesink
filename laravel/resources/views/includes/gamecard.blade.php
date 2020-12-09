<?php
if (!isset($g)) return null;
?>

<a href='/play/{{$g->shortlink}}'><div class='gamecard'>
  <span class='title'>{{$g->name}}<span>
  <div class='gameimg' style='background-image: url({{$g->bannerlink()}})'></div>
</div></a>
