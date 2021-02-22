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

//Rutas del controlador Tarjetas
Route::get('/lista-tarjetas/{id_tarjeta?}', array(
    'as' => 'tarjetas',
    'middleware' => 'auth',
    'uses' => 'TarjetasController@index'
));

Route::get('/tarjetaslista', array(
    'as' => 'tarjetaslista',
    'uses' => 'TarjetasController@tarjetaslista'
));

Route::post('/guardar-tarjeta', array(
    'as' => 'saveTarjeta',
    'middleware' => 'auth',
    'uses' => 'TarjetasController@saveTarjeta'
));

Route::post('/update-tarjeta/{id_tarjeta_editar}', array(
    'as' => 'updateTarjeta',
    'middleware' => 'auth',
    'uses' => 'TarjetasController@updateTarjeta'
));

Route::get('/delete-tarjeta/{id_tarjeta}', array(
    'as' => 'deleteTarjeta',
    'middleware' => 'auth',
    'uses' => 'TarjetasController@deleteTarjeta'
));

//Rutas del controlador Memorándums
Route::get('/lista-memorandums/{id_memorandum?}', array(
    'as' => 'memorandums',
    'middleware' => 'auth',
    'uses' => 'MemorandumsController@index'
));

Route::get('/memorandumslista', array(
    'as' => 'memorandumslista',
    'uses' => 'MemorandumsController@memorandumslista'
));

Route::post('/guardar-memorandum', array(
    'as' => 'saveMemorandum',
    'middleware' => 'auth',
    'uses' => 'MemorandumsController@saveMemorandum'
));

Route::post('/update-memorandum/{id_memorandum_editar}', array(
    'as' => 'updateMemorandum',
    'middleware' => 'auth',
    'uses' => 'MemorandumsController@updateMemorandum'
));

Route::get('/delete-memorandum/{id_memorandum}', array(
    'as' => 'deleteMemorandum',
    'middleware' => 'auth',
    'uses' => 'MemorandumsController@deleteMemorandum'
));


//Logout
Route::get('/logout', array(
    'as' => 'logout',
    'middleware' => 'auth',
    'uses' => '\App\Http\Controllers\Auth\LoginController@logout'
));
