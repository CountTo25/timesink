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
      return redirect('/dev/game/'.$g->id)->with('messages', $msgstack);
    }

    //newgamegit
    public function newgamegit(Request $request) {
      $msgstack = [];
      if (!$request->username) {
        $msgstack[] = 'No github username specified';
        return back()->with('messages', $msgstack);
      }
      if (!$request->repo) {
        $msgstack[] = 'No repo name specified';
        return back()->with('messages', $msgstack);
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

      $url="https://api.github.com/repos/$request->username/$request->repo/zipball";
      $msgstack[] = 'Getting repo @ '.$url;
      $agent = 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1)';
      //https://api.github.com/repos/countto25/queslarqqol/releases
      $ch = curl_init();
      curl_setopt($ch, CURLOPT_BINARYTRANSFER, 1);
      curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
      curl_setopt($ch, CURLOPT_URL, $url);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
      curl_setopt($ch, CURLOPT_SSLVERSION,CURL_SSLVERSION_TLSv1_2);
      curl_setopt($ch, CURLOPT_USERAGENT, $agent);
      $data = curl_exec ($ch);
      $error = curl_error($ch);
      curl_close ($ch);
      $decode = json_decode($data, true);
      if (gettype($decode) == 'array') {
          $msgstack[] = 'Nothing found. Check either username or repo';
          return back()->with('messages', $msgstack);
      }
      Storage::disk('games')->makeDirectory($request->shortlink);
      Storage::disk('games')->makeDirectory($request->shortlink.'/docs');
      Storage::disk('games')->makeDirectory($request->shortlink.'/1');
      $file = fopen(Storage::disk('games')->getDriver()->getAdapter()->getPathPrefix().$request->shortlink.'/gitzip.zip', "w+");
      fputs($file, $data);
      fclose($file);
      $msgstack[] = 'Got zipball from git';
      $f = Storage::disk('games')->getDriver()->getAdapter()->getPathPrefix().$request->shortlink.'/gitzip.zip';
      GameCooker::unzipGame($f, $request->shortlink, '1', $msgstack);
      GameCooker::cleanupgit($request->shortlink, '1', $msgstack);
      $g->save();
      return redirect('/dev/game/'.$g->id)->with('messages', $msgstack);
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

    public function snatchgit(Request $request, $id) {
      $g = Game::where('id', $id)->first();
      $msgstack = [];
      if (!$request->username) {
        $msgstack[] = 'No github username specified';
        return back()->with('messages', $msgstack);
      }
      if (!$request->repo) {
        $msgstack[] = 'No repo name specified';
        return back()->with('messages', $msgstack);
      }
      $url="https://api.github.com/repos/$request->username/$request->repo/zipball";
      $msgstack[] = 'Getting repo @ '.$url;
      $agent = 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1)';
      //https://api.github.com/repos/countto25/queslarqqol/releases
      $ch = curl_init();
      curl_setopt($ch, CURLOPT_BINARYTRANSFER, 1);
      curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
      curl_setopt($ch, CURLOPT_URL, $url);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
      curl_setopt($ch, CURLOPT_SSLVERSION,CURL_SSLVERSION_TLSv1_2);
      curl_setopt($ch, CURLOPT_USERAGENT, $agent);
      $data = curl_exec ($ch);
      $error = curl_error($ch);
      curl_close ($ch);
      $decode = json_decode($data, true);
      if (gettype($decode) == 'array') {
          $msgstack[] = 'Nothing found. Check either username or repo';
          return back()->with('messages', $msgstack);
      }

      $file = fopen(Storage::disk('games')->getDriver()->getAdapter()->getPathPrefix().$g->shortlink.'/gitzip.zip', "w+");
      fputs($file, $data);
      fclose($file);
      $msgstack[] = 'Got zipball from git';
      $g->newVersion();

      $msgstack[] = 'Version bump -> '.$g->version;
      $f = Storage::disk('games')->getDriver()->getAdapter()->getPathPrefix().$g->shortlink.'/gitzip.zip';
      GameCooker::unzipGame($f, $g->shortlink, $g->version, $msgstack);
      GameCooker::cleanupgit($g->shortlink, $g->version, $msgstack);
      return back()->with('messages', $msgstack);
    }
}
