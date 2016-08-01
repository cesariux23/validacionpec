<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/', function () {
    return redirect()->route('base.pendientes.index');
});

Route::auth();
Route::group(['prefix' => 'base'], function ()
    {
        Route::resource('todos','BaseController', ['only' => ['index','show']]);
        Route::resource('pendientes','BaseController', ['only' => ['index','show']]);
        Route::resource('incompletos', 'BaseController', ['only' => ['index']]);
        Route::resource('validos', 'BaseController', ['only' => ['index']]);
        Route::resource('ensasa', 'BaseController', ['only' => ['index']]);
    }
);
Route::get('/base/export',
  [
    'as' => 'base.export', 'uses' => 'BaseController@export'
  ]
);


Route::resource('emision','EmisionController');
Route::resource('validacion','ValidacionController');

Route::get('/home', 'HomeController@index');
