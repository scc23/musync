<?php

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
    if (Auth::check()) {
        return redirect()->route('home');
    } else {
        return view('welcome');
    }
});

Route::get('login/spotify', 'Auth\LoginController@redirectToProvider')
     ->name('login');
Route::get('login/spotify/callback', 'Auth\LoginController@handleProviderCallback');
Route::post('logout', 'Auth\LoginController@logout')
     ->name('logout');

Route::get('/home', 'HomeController@index')
     ->name('home');

Route::post('/rooms', 'RoomController@register')
     ->middleware('auth');
Route::get('/room/{id}', 'RoomController@show')
     ->middleware('auth', 'auth.room')
     ->name('room.id');
Route::post('/room/{id}/membership', 'RoomMembershipController@join')
     ->middleware('auth', 'auth.room')
     ->name('room.membership.create');

