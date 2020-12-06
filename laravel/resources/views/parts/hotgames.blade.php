<?php
  use App\Models\Game;

  $all = Game::all();
?>

@foreach($all as $g)
  @include ('includes.gamecard')
@endforeach
