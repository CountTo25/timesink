<?php

use Illuminate\Support\Facades\Route;
use App\Models\Game;
use App\Models\User;
use App\Mail\Registration;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Dev as DevController;
use App\Http\Controllers\ClientApi as ClientApiController;
use App\Http\Controllers\ClientApiPost as ClientApiPostController;
use Illuminate\Support\Facades\Mail;

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

Route::get('/auth', function(){return view('includes.authform');})->name('login');

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

Route::get('/', function () {return view('index');})->name('index');

Route::get('/signup', function() {return view('signup');});

Route::get('/testmail', function() {
  Mail::to('rikiworo@gmail.com')->send(new Registration('test'));
  return 'kek';
});

Route::get('/roadmap', function() {return view('roadmap');});

Route::middleware(['noauth'])->group(function(){
  Route::get('/signup', function() {return view('signup');});
  Route::get('/auth', function(){return view('includes.authform');})->name('login');
  Route::post('/signup', function(Request $request) {
    if (User::where('email', $request->email)->first()) {
      return redirect('/signup')
        ->with('popup', 'User with such email already exists')
        ->with('popup_title', 'Error');
    }
    $u = new User();
    $u->name = $request->name;
    $u->email = $request->email;
    $u->password = Hash::make($request->password);
    $u->role = 'user';
    $u->save();
    $u->generateVerifyToken();
    Mail::to($u->email)->send(new Registration($u->verify_token, $u->email));
    return redirect('/')->with('popup','Check your email for verification link');
  });
  Route::get('/verify', function(Request $request){
    $u = User::where('email', $request->email)->first();
    if ($u->verify_token == $request->key) {
      $u->verified = 1;
      $u->save();
      return route('login');
    }
  });
});

Route::middleware(['auth', 'dev'])->group(function(){
  Route::get('/dev', [DevController::class, 'home']);
  Route::get('/dev/game/{id}', [DevController::class, 'game']);
  Route::get('/dev/upload/new', [DevController::class, 'newgame']);
  Route::post('/dev/game/{id}/snatchgit', [DevController::class, 'snatchgit']);
  Route::post('/dev/upload/new', [DevController::class, 'postnewgame']);
  Route::post('/dev/upload/newfromgit', [DevController::class, 'newgamegit']);
  Route::post('dev/game/{id}/update', [DevController::class, 'pushUpdate']);
  Route::post('dev/game/{id}/img', [DevController::class, 'postupdatepic']);
});



Route::middleware(['auth'])->group(function(){
  Route::get('/logout', function(){Auth::logout(); return back();});
});



Route::middleware(['throttle:5,1'])->group(function() {
  Route::post('/clientapi/{method}', ClientApiPostController::class);
});
Route::get('/clientapi/{method}', ClientApiController::class);
