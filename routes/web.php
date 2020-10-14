<?php

use App\Http\Controllers\AlbumController;
use Illuminate\Support\Facades\Route;
use Spatie\Permission\Models\Role;

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
    return redirect()->route('inicio');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::get('crearAlbum', 'DropZone@index')->middleware('auth')->name('dropzone.index');

Route::post('dropzone/upload', 'DropZone@upload')->name('dropzone.upload')->middleware('auth');
Route::post('dropzone/portada', 'DropZone@portada')->name('dropzone.portada')->middleware('auth');

Route::get('dropzone/fetch', 'DropZone@fetch')->name('dropzone.fetch')->middleware('auth');

Route::get('zip', 'DropZone@zip')->name('dropzone.zip')->middleware('auth');

Route::get('redimensionar', 'DropZone@redimensionar')->name('dropzone.redimensionar')->middleware('auth');

Route::get('dropzone/delete', 'DropZone@delete')->name('dropzone.delete')->middleware('auth');

Route::get('/album', 'AlbumController@index')->middleware('auth')->name('inicio');


Route::get('album/guardar', 'AlbumController@guardarAlbum')->name('dropzone.guardar')->middleware('auth');

Route::get('album/ver/{id}', 'AlbumController@verAlbum')->name('album.ver')->middleware('auth');

Route::get('album/descargar/{id}', 'DropZone@redimensionar')->name('album.descargar')->middleware('auth');


Route::get('/usuarios','AlbumController@usuarios')->name('usuarios')->middleware('auth');

Route::get('/migrate', function () {
    //return public_path();
Auth::user()->assignRole('admin');
    die();
    Artisan::call('storage:link');
});