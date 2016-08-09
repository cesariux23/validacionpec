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
Route::auth();

Route::get('/', function () {
  if(Auth::user()){
    switch (Auth::user()->rol) {
      case '0':
        # code...
      case '1':
        # code...
        return redirect()->route('base.validos.index');
        break;
      case '2':
        # code...
        return redirect()->route('base.pendientes.index');
        break;
        case '3':
          # code...
          return redirect()->route('emision.index',['emitido'=>0]);
          break;
      default:
        # code...
        return redirect('/login');
        break;
    }
  }
  else{
    return redirect('login');
  }
});

Route::group(['prefix' => 'base'], function ()
    {
        Route::resource('todos','BaseController', ['only' => ['index','show']]);
        Route::resource('pendientes','BaseController', ['only' => ['index','show']]);
        Route::resource('incompletos', 'BaseController', ['only' => ['index']]);
        Route::resource('validos', 'BaseController', ['only' => ['index']]);
        Route::resource('finalizados', 'BaseController', ['only' => ['index']]);
        Route::resource('ensasa', 'BaseController', ['only' => ['index']]);
    }
);
Route::get('/base/export',
  [
    'as' => 'base.export', 'uses' => 'BaseController@export'
  ]
);
Route::get('/emision/export',
  [
    'as' => 'emision.export', 'uses' => 'EmisionController@export'
  ]
);


Route::resource('emision','EmisionController');
Route::resource('validacion','ValidacionController');

Route::get('/importar/base',
  [
    'as' => 'importar.base', 'uses' => 'ImportarController@base'
  ]
);
Route::get('/importar/certificados',
  [
    'as' => 'importar.certificados', 'uses' => 'ImportarController@certificados'
  ]
);

Route::post('/importar/base',
  [
    'as' => 'importar.base', 'uses' => 'ImportarController@base'
  ]
);
Route::post('/importar/certificados',
  [
    'as' => 'importar.certificados', 'uses' => 'ImportarController@certificados'
  ]
);
