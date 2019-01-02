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

Route::group(['middleware' => ['auth']], function() {
    Route::get('/', function() {
        return redirect('/manager/home');
    });

    // Vistas principales
    Route::get('/manager/home', 'HomeController@dashboard')->name('manager');
    Route::get('/manager/videos', 'VideoController@manager')->name('video_manager');
    Route::get('/manager/listas', 'ListaController@manager')->name('lista_manager');
    Route::get('/manager/pantallas', 'PantallaController@manager')->name('pantalla_manager');

    // ABM Videos
    Route::get('/video/popup', 'VideoController@modalVideoPopup');
    Route::get('/video/editar', 'VideoController@modalEditarInfo');
    Route::get('/video/eliminar', 'VideoController@modalEliminarVideo');
    Route::post('/manager/subir_video', 'VideoController@subirVideo')->name('subir_video');
    Route::post('/manager/editar_video', 'VideoController@editarVideo')->name('guardar_info_video');
    Route::post('/manager/eliminar_video', 'VideoController@eliminarVideo')->name('eliminar_video');

    // ABM Listas
    Route::get('/lista/editar', 'ListaController@vistaEditarLista')->name('lista_editar');
    Route::get('/lista/asignar', 'ListaController@modalAsignarLista');
    Route::post('/manager/crear_lista', 'ListaController@crearLista')->name('crear_lista');
    Route::post('/manager/editar_lista', 'ListaController@editarLista')->name('guardar_info_lista');
    Route::post('/manager/eliminar_lista', 'ListaController@eliminarLista')->name('eliminar_lista');
    Route::post('/manager/asignar_lista', 'ListaController@asignarLista')->name('asignar_lista');

    // ABM Pantallas
    Route::get('/pantalla/editar', 'PantallaController@modalEditarPantalla');
    Route::get('/pantalla/crear', 'PantallaController@modalCrearPantalla');
    Route::get('/pantalla/eliminar', 'PantallaController@modalEliminarPantalla');
    Route::get('/pantalla/asignar', 'PantallaController@modalAsignarLista');
    Route::post('/manager/crear_pantalla', 'PantallaController@crearPantalla')->name('crear_pantalla');
    Route::post('/manager/editar_pantalla', 'PantallaController@editarPantalla')->name('editar_pantalla');
    Route::post('/manager/eliminar_pantalla', 'PantallaController@eliminarPantalla')->name('eliminar_pantalla');
    Route::post('/manager/toggle_pantalla', 'PantallaController@toggleHabilitacionPantalla');
    Route::post('/manager/asignar_listas', 'PantallaController@asignarListasAPantallas')->name('asignar_pantalla');

    // Polling / SSE
    Route::get('/proceso/{id}', 'VideoController@getProcesoVideo');
});

Route::get('/pantalla/{pantalla_id}/get_lista', 'SincroController@getLista');
Route::post('/pantalla/{pantalla_id}/beacon', 'NowPlayingController@registrar');

Route::get('/pantalla/{pantalla_id}/dl_video/{video_id}', 'SincroController@getVideoDL');

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
