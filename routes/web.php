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

Route::get('/verificar-oficio/{id_oficio?}', array(
    'as' => 'verificarOficios',
    'uses' => 'OficiosController@verificarOficios'
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

Route::get('/cancelar-oficio/{id_oficio_cancelar}/{firma}/{motivo}', array(
    'as' => 'cancelarOficio',
    'middleware' => 'auth',
    'uses' => 'OficiosController@cancelarOficio'
));

Route::get('/mostrar-oficio-cancelado/{id_oficio}', array(
    'as' => 'oficioCancelado',
    'middleware' => 'auth',
    'uses' => 'OficiosController@oficioCancelado'
));

//Rutas del controlador Circulares
Route::get('/lista-circulares/{id_circular?}', array(
	'as' => 'circulares',
	'middleware' => 'auth',
	'uses' => 'CircularesController@index'
));

Route::get('/verificar-circular/{id_circular?}', array(
    'as' => 'verificarCirculares',
    'uses' => 'CircularesController@verificarCirculares'
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

Route::get('/cancelar-circular/{id_circular_cancelar}/{firma}/{motivo}', array(
    'as' => 'cancelarCircular',
    'middleware' => 'auth',
    'uses' => 'CircularesController@cancelarCircular'
));

Route::get('/mostrar-circular-cancelado/{id_circular}', array(
    'as' => 'circularCancelado',
    'middleware' => 'auth',
    'uses' => 'CircularesController@circularCancelado'
));

//Rutas del controlador Tarjetas
Route::get('/lista-tarjetas/{id_tarjeta?}', array(
    'as' => 'tarjetas',
    'middleware' => 'auth',
    'uses' => 'TarjetasController@index'
));

Route::get('/verificar-tarjeta/{id_tarjeta?}', array(
    'as' => 'verificarTarjetas',
    'uses' => 'TarjetasController@verificarTarjetas'
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

Route::get('/cancelar-tarjeta/{id_tarjeta_cancelar}/{firma}/{motivo}', array(
    'as' => 'cancelarTarjeta',
    'middleware' => 'auth',
    'uses' => 'TarjetasController@cancelarTarjeta'
));

Route::get('/mostrar-tarjeta-cancelado/{id_tarjeta}', array(
    'as' => 'tarjetaCancelado',
    'middleware' => 'auth',
    'uses' => 'TarjetasController@tarjetaCancelado'
));

//Rutas del controlador Memorándums
Route::get('/lista-memorandums/{id_memorandum?}', array(
    'as' => 'memorandums',
    'middleware' => 'auth',
    'uses' => 'MemorandumsController@index'
));

Route::get('/verificar-memorandums/{id_tarjeta?}', array(
    'as' => 'verificarMemorandums',
    'uses' => 'MemorandumsController@verificarMemorandums'
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

Route::get('/cancelar-memorandum/{id_memorandum_cancelar}/{firma}/{motivo}', array(
    'as' => 'cancelarMemorandum',
    'middleware' => 'auth',
    'uses' => 'MemorandumsController@cancelarMemorandum'
));

Route::get('/mostrar-memorandum-cancelado/{id_memorandum}', array(
    'as' => 'memorandumCancelado',
    'middleware' => 'auth',
    'uses' => 'MemorandumsController@memorandumCancelado'
));

//Logout
Route::get('/logout', array(
    'as' => 'logout',
    'middleware' => 'auth',
    'uses' => '\App\Http\Controllers\Auth\LoginController@logout'
));
