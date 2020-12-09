<?php
if (!isset($g)) return 'kek';
?>

<a href='/dev/game/{{$g->id}}'><div class='gamecard devblock'>
  <span class='title'>{{$g->name}}<span>
    <div class='gameimg' style='background-image: url({{$g->bannerlink()}})'></div>
</div></a>
