<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Cloudsave;
use App\Models\Game;


class ClientApiPost extends Controller
{
  public function __invoke($method, Request $request) {
    if (!Auth()->check()) return 'Player not logged in';
    switch($method){
      case 'cloudsave':
        $dt = $request->savedata;
        $pattern = '/\/games\/(.*?)\//';
        $ref = $request->headers->get('referer');
        preg_match($pattern, $request->headers->get('referer'), $name);
        $id = Game::where('shortlink',$name[1])->first()->id;
        $save = Cloudsave::where('for_game', $id)->where('for_user', Auth::user()->id)->first();
        if ($save === null) {
          $save = new Cloudsave();
          $save->for_game = 1;
          $save->for_user = Auth::user()->id;
        }
        $save->data = $dt;
        $save->save();
        return response()->json(['success'=>'Cloudsave ok']);
        break;
      default:
        return response()->json(['error'=>"Unknown method '$method'"]);
        break;
    }
  }
}
