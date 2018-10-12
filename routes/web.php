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

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::post('/rooms', 'RoomController@create')->middleware('auth');
Route::get('/room/{id}', function($id) {
    # TODO: Refactor with controller for user-room connections.
    return view('room', ['room_id' => $id]);
})->name('room_id');

Route::post('/tracks', 'TrackController@create')->middleware('auth');