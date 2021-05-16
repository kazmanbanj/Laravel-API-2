<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/v1/notes', 'App\Http\Controllers\NotesController@allNotes');
Route::post('/v1/notes', 'App\Http\Controllers\NotesController@store');
Route::get('v1/note/{id}', 'App\Http\Controllers\NotesController@show');

Route::put('v1/note/{id}', 'App\Http\Controllers\NotesController@update');

Route::delete('v1/notes/{id}', 'App\Http\Controllers\NotesController@permanentDelete'); //this on first permanent delete models
Route::delete('v2/notes/{id}', 'App\Http\Controllers\NotesController@softDelete');
Route::get('v2/notes/withsoftdelete', 'App\Http\Controllers\NotesController@notesWithSoftDelete');
Route::get('v2/notes/softdeleted', 'App\Http\Controllers\NotesController@softDeleted');
Route::patch('/v1/notes/{id}', 'App\Http\Controllers\NotesController@restore');
Route::delete('v3/notes/{id}', 'App\Http\Controllers\NotesController@permanentDeleteSoftDeleted');
