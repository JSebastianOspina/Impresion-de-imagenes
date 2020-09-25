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

Route::get('dropzone', 'DropZone@index');

Route::post('dropzone/upload', 'DropZone@upload')->name('dropzone.upload');

Route::get('dropzone/fetch', 'DropZone@fetch')->name('dropzone.fetch');

Route::get('zip', 'DropZone@zip')->name('dropzone.zip');

Route::get('redimensionar', 'DropZone@redimensionar')->name('dropzone.redimensionar');

Route::get('dropzone/delete', 'DropZone@delete')->name('dropzone.delete');



