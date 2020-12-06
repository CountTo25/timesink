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
      $fref = "/$this->shortlink/$this->version/";
      if (Storage::disk('games')->exists($fref)) {
        //find index;
        if (Storage::disk('games')->exists($fref.'index.html')) {
          echo "/games/$fref/index.html";
        } else {
          echo 'cant find index';
        }
      } else {
        view('includes.game404');
      }
    }
}
