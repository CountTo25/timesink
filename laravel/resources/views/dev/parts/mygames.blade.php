<?php
use App\Models\Game;

  $games = Game::where('owner', Auth::user()->id)->get();
?>
<div>
  @foreach ($games as $g)
    @include('dev.parts.gamecarddev', ['g'=>$g])
  @endforeach
</div>
