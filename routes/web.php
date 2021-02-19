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


//Rutas del controlador Oficios
Route::get('/lista-oficios/{id_oficio?}', array(
	'as' => 'oficios',
	'middleware' => 'auth',
	'uses' => 'OficiosController@index'
));

Route::get('/oficioslista', array(
    'as' => 'oficioslista',
    'uses' => 'OficiosController@oficioslista'
));

Route::post('/guardar-oficio', array(
    'as' => 'saveOficio',
    'middleware' => 'auth',
    'uses' => 'OficiosController@saveOficio'
));

Route::post('/update-oficio/{id_oficio_editar}', array(
    'as' => 'updateOficio',
    'middleware' => 'auth',
    'uses' => 'OficiosController@updateOficio'
));

Route::get('/delete-oficio/{id_oficio}', array(
    'as' => 'oficioDelete',
    'middleware' => 'auth',
    'uses' => 'OficiosController@deleteOficio'
));

//Rutas del controlador Circulares
Route::get('/lista-circulares/{id_circular?}', array(
	'as' => 'circulares',
	'middleware' => 'auth',
	'uses' => 'CircularesController@index'
));

Route::get('/circulareslista', array(
    'as' => 'circulareslista',
    'uses' => 'CircularesController@circulareslista'
));

Route::post('/guardar-circular', array(
    'as' => 'saveCircular',
    'middleware' => 'auth',
    'uses' => 'CircularesController@saveCircular'
));

Route::post('/update-circular/{id_circular_editar}', array(
    'as' => 'updateCircular',
    'middleware' => 'auth',
    'uses' => 'CircularesController@updateCircular'
));

Route::get('/delete-circular/{id_circular}', array(
    'as' => 'deleteCircular',
    'middleware' => 'auth',
    'uses' => 'CircularesController@deleteCircular'
));


