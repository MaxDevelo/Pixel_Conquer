<?php

use Illuminate\Support\Facades\Route;
use App\Http\Middleware\EnsureMyUserIsAuthenticated;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PixelController;
use Illuminate\Http\Request;




Route::group([], function () {
  Route::get('/', [UserController::class,'home'])->name('home');
  Route::get('/home', [UserController::class,'home'])->name('home');
  Route::get('signin', [UserController::class,'signin'])->name('signin');
  Route::post('authenticate', [UserController::class,'authenticate'])->name('authenticate');
  Route::post('adduser', [UserController::class,'adduser'])->name('adduser');
  Route::get('signup', [UserController::class,'signup'])->name('signup');
  Route::post('/pixel', [PixelController::class, 'store'])->name('pixel');
  Route::fallback(function () {
    return redirect('/');
});
Route::get('/pusher', function(Request $request) {
  $data = $request->all();
  return view('welcome')->with('data', $data);
});

});

//Route::prefix('admin')->group( function () {

Route::prefix('admin')->middleware('auth.myuser')->group(function () {

	Route::get('account', [UserController::class,'account'])->name('account');
  Route::get('formpassword', [UserController::class,'formpassword'])->name('formpassword');
	Route::get('signout', [UserController::class,'signout'])->name('signout');
  Route::get('changepassword', [UserController::class,'changepassword'])->name('changepassword');
  Route::get('deleteuser', [UserController::class,'deleteuser'])->name('deleteuser');

});
