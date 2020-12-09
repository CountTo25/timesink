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
      $msgstack = [];
      $g = Game::where('id', $id)->first();
      if ($g->owner != Auth::user()->id) {
        $msgstack[] = 'Thats not your game, smartass';
        return back()->with('messages', $msgstack);
      }
      if ($request->package->getClientOriginalExtension() <> 'zip') {
        $msgstack[] = 'Not a .zip file';
        return back()->with('messages',$msgstack);
      }
      $f = $request->package;
      GameCooker::unzipGame($f, $g->shortlink, ($g->version+1), $msgstack);
      $g->newVersion();
      $msgstack[] = 'Version bump -> '.$g->version;
      return back()->with('messages', $msgstack);
    }

    public function newgame(Request $request) {
      return view('dev.newgame');
    }

    public function postnewgame(Request $request) {
      //TODO: move unzip to cooker DONE keep to remember
      $msgstack = [];
      if ($request->package->getClientOriginalExtension() <> 'zip') {
        $msgstack[] = 'Not a .zip file';
        return redirect('/dev/upload/new')->with('messages',$msgstack);
      }
      if (Game::where('shortlink', $request->shortlink)->first()) {
        $msgstack[] = 'Shortlink already occupied';
        return redirect('/dev/upload/new')->with('messages',$msgstack);
      }
      $msgstack[] = 'Creating new game entry...';
      $g = new Game();
      $g->shortlink = $request->shortlink;
      $g->name = $request->game_name;
      $g->owner = Auth::user()->id;

      $f = $request->package;
      $msgstack[] = 'Unzipping game...';
      GameCooker::unzipGame($f, $request->shortlink, '1', $msgstack);

      Storage::disk('games')->makeDirectory($request->shortlink.'/docs');
      if ($request->file('pic')) {
        Storage::disk('games')->putFileAs($request->shortlink.'/docs', $request->file('pic'), 'banner.png');
      }

      $g->save();
      $msgstack[] = 'Saved game entry';
      $msgstack[] = 'Finished!';
      return back()->with('messages', $msgstack);
    }

    public function postupdatepic(Request $request, $id) {
      $msgstack = [];
      $g = Game::where('id', $id)->first();
      if ($g->owner != Auth::user()->id) {
        $msgstack[] = 'Thats not your game, smartass';
        return back()->with('messages', $msgstack);
      }

      if (!$request->file('bannerpic')) {
        $msgstack[] = 'No file passed';
        return back()->with('messages', $msgstack);
      }

      $msgstack[] = 'Trying to upload...';

      Storage::disk('games')
        ->putFileAs($g->shortlink.'/docs/', $request->file('bannerpic'), 'banner.png');

      $msgstack[] = 'Upload successfull';
      return back()->with('messages', $msgstack);
    }
}
