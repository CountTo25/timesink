<?php
  use App\Models\Game;

  $all = Game::all();
?>
<div>
@foreach($all as $g)
  @include ('includes.gamecard')
@endforeach
</div>
