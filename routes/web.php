<?php

use Illuminate\Support\Facades\Route;

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
    return view('welcome');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::get('dropzone', 'DropZone@index')->middleware('auth')->name('dropzone.index');

Route::post('dropzone/upload', 'DropZone@upload')->name('dropzone.upload')->middleware('auth');
Route::post('dropzone/portada', 'DropZone@portada')->name('dropzone.portada')->middleware('auth');

Route::get('dropzone/fetch', 'DropZone@fetch')->name('dropzone.fetch')->middleware('auth');

Route::get('zip', 'DropZone@zip')->name('dropzone.zip')->middleware('auth');

Route::get('redimensionar', 'DropZone@redimensionar')->name('dropzone.redimensionar')->middleware('auth');

Route::get('dropzone/delete', 'DropZone@delete')->name('dropzone.delete')->middleware('auth');

Route::get('/album', 'AlbumController@index')->middleware('auth')->name('inicio');


Route::get('album/guardar', 'AlbumController@guardarAlbum')->name('dropzone.guardar')->middleware('auth');

Route::get('album/ver/{id}', 'AlbumController@verAlbum')->name('album.ver')->middleware('auth');

Route::get('album/descargar', 'AlbumController@guardarAlbum')->name('album.descargar')->middleware('auth');




