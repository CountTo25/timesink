<?php

namespace App\Models;
use Storage;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Game extends Model
{
    use HasFactory;

    function view() {
      //shows game for player
      $fref = "/$this->shortlink/";
      if (Storage::disk('games')->exists($fref)) {
        //find index;
        if (Storage::disk('games')->exists($fref.'index.html')) {
          //game ok, embeed it;
          $index = Storage::disk('games')->get($fref.'index.html');
          $index = htmlspecialchars($index);
          return preg_replace( "/\r|\n/", "", $index);
        }
      } else {
        view('includes.game404');
      }
    }
}
