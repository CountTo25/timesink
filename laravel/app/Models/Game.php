<?php

namespace App\Models;
use Storage;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;


class Game extends Model
{
    use HasFactory;


    function owner() {
      return User::where('id', $this->owner)->first();
    }

    function newVersion() {
      $this->version = $this->version+1;
      $this->save();
      return $this->version;
    }

    function view() {
      //shows game for player
      $fref = "/$this->shortlink/";
      if (Storage::disk('games')->exists($fref)) {
        //find index;
        if (Storage::disk('games')->exists($fref.'index.html')) {
          //game ok, embeed it;
          $index = Storage::disk('games')->get($fref.'index.html');
          //isolate localStorage
            //ghettto way?
            $index = str_replace('localStorage.setItem(\'',
                                 'localStorage.setItem(\'timesink_'.$this->shortlink.'__',
                                 $index);
            $index = str_replace('localStorage.getItem(\'',
                                 'localStorage.getItem(\'timesink_'.$this->shortlink.'__',
                                 $index);
            //in case anyone uses "
            $index = str_replace('localStorage.setItem("',
                                 'localStorage.setItem("timesink_'.$this->shortlink.'__',
                                 $index);
            $index = str_replace('localStorage.getItem("',
                                 'localStorage.getItem("timesink_'.$this->shortlink.'__',
                                 $index);
          //
          $index = htmlspecialchars($index);


          //later
          //          return preg_replace( "/\r|\n/", "", $index);
          echo "/games/$fref/$this->version/index.html";
          //echo Storage::disk('games')->url($fref.'index.html');
          //return Storage::disk('games')->url($fref.'index.html');

        }
      } else {
        view('includes.game404');
      }
    }
}
