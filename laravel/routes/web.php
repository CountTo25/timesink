<?php

use Illuminate\Support\Facades\Route;
use App\Models\Game;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Dev as DevController;
use App\Http\Controllers\ClientApi as ClientApiController;
use App\Http\Controllers\ClientApiPost as ClientApiPostController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

//TODO: cleanup, controllers


Route::get('/play/{game}', function ($game) {
  $game = Game::where('shortlink', $game)->first();
  return view('gamepage')->with('game', $game);
});

Route::get('/auth', function(){view('includes.authform');})->name('login');

Route::post('/auth', function(Request $request) {
  $cred = $request->only('email', 'password');
  if (Auth::attempt($cred)) {
    if (Auth::user()->group=='admin') {
      return redirect('/admin');
    } else {
      return redirect('/');
    }
  } else {
    echo 'err';
  }
});


Route::get('/', function () {return view('index');});


Route::middleware(['auth'])->group(function(){
  Route::get('/dev', [DevController::class, 'home']);
  Route::get('/dev/game/{id}', [DevController::class, 'game']);
  Route::post('dev/game/{id}/update', [DevController::class, 'pushUpdate']);
});

Route::middleware(['auth'])->group(function() {
  //TODO: auth-soft without redirect to auth
  Route::get('/clientapi/{method}', ClientApiController::class);
  Route::post('/clientapi/{method}', ClientApiPostController::class);
});
