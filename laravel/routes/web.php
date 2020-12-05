<?php

use Illuminate\Support\Facades\Route;
use App\Models\Game;
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

Route::get('/', function () {
    return view('index');
});

Route::get('/play/{game}', function ($game) {
  $game = Game::where('shortlink', $game)->first();
  return view('gamepage')->with('game', $game);
});
