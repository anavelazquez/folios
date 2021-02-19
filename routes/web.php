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

Auth::routes();

Route::get('/', function () {
    if(!Auth::check()){
        return view('auth.login');
    }else{
        return Redirect::action('OficiosController@index');
    }
});

Route::get('/home', array(
	'as' => 'home',
	'middleware' => 'auth',
	'uses' => 'HomeController@index'
));

//Rutas del controlador Oficios
Route::get('/lista-oficios/{id_oficio?}', array(
	'as' => 'oficios',
	'middleware' => 'auth',
	'uses' => 'OficiosController@index'
));

