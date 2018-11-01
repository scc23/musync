<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::get('/rooms', 'RoomAPIController@getAllRooms');
Route::post('/rooms', 'RoomAPIController@createRoom');
Route::get('/room/{id}', 'RoomAPIController@getRoomById');
Route::post('/room/{id}/membership', 'RoomMembershipAPIController@joinRoom');
