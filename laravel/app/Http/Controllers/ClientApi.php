<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Cloudsave;
use App\Models\Game;

class ClientApi extends Controller
{
    public function __invoke($method, Request $request) {
      if (!Auth::check()) return 'Player not logged in'; //tbh there's middleware remove it on cleanup pass
      switch($method){
        case 'cloudsave':
          $pattern = '/\/games\/(.*?)\//';
          $ref = $request->headers->get('referer');
          preg_match($pattern, $request->headers->get('referer'), $name);
          $id = Game::where('shortlink',$name[1])->first()->id;
          $save = Cloudsave::where('for_game', $id)->where('for_user', Auth::user()->id)->first();
          if ($save === null) {
            return response()->json(['error'=>'No cloudsave found for current user']);
          } else {
            return response()->json(['success'=>'Save retrieved', 'cloudsave' => $save->data]);
          }
          break;
        default:
          return response()->json(['error'=>"Unknown method '$method'"]);
          break;
      }
    }
}
