<?php

namespace App\Http\Controllers;

use App\Models\Game;

use GameCooker;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\File;
use Illuminate\Support\Facades\Storage;


class Dev extends Controller
{
    //
    public function home() {
      return view('dev.home');
    }
    //TODO: check ownership via middleware
    public function game($id) {
      $g = Game::where('id', $id)->first();
      if ($g->owner != Auth::user()->id) return 'nope';
      return view('dev.game', ['g'=>$g]);
    }

    public function pushUpdate(Request $request, $id) {
      $g = Game::where('id', $id)->first();
      if ($g->owner != Auth::user()->id) return 'nope';
      $ver = $g->newVersion();
      $html = $request->html;
      $css = $request->css;
      $js = $request->js;
      //GHETTO INBOUND, CLEANUP LATER
      //modify HTML to suit stuff
      //CONSIDERING EVERYTHING IS VERIFIED
      //make Whatever::fixLS();

      Storage::disk('games')->makeDirectory("/$g->shortlink/$ver");

      $isolate = file_get_contents($html);
      file_put_contents($html,GameCooker::processLS($isolate, $g->shortlink));
      $path = "/$g->shortlink/$ver";
      Storage::disk('games')->putFileAs($path, $html, 'index.html');

      if (isset($css)) {
        foreach ($css as $c) {
          Storage::disk('games')->putFileAs($path, $c, $c->getClientOriginalName());
        }
      }

      if (isset($js)) {
        foreach ($js as $j) {
          unset($isolate);
          $isolate = file_get_contents($j);
          file_put_contents($j,GameCooker::processLS($isolate, $g->shortlink));
          Storage::disk('games')->putFileAs($path, $j, $j->getClientOriginalName());
          }
      }
      return redirect()->back();
    }
}
