<?php

namespace App\Http\Controllers;

use App\Models\Game;

use GameCooker;
use ZipArchive;

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
      $f = $request->package;
      GameCooker::unzipGame($f, $g->shortlink, ($g->version+1));
      $g->newVersion();
      //return redirect()->back();
    }

    public function newgame(Request $request) {
      return view('dev.newgame');
    }

    public function postnewgame(Request $request) {
      //TODO: move unzip to cooker DONE keep to remember
      if ($request->package->getClientOriginalExtension() <> 'zip')
        return redirect('/dev/upload/new')->with('error','Not a .zip file');
      if (Game::where('shortlink', $request->shortlink)->first())
        return redirect('/dev/upload/new')->with('error','Shortlink already occupied');
      $g = new Game();
      $g->shortlink = $request->shortlink;

      $g->name = $request->game_name;
      $g->owner = Auth::user()->id;

      $f = $request->package;
      GameCooker::unzipGame($f, $request->shortlink, '1');

      $g->save();
    }
}
