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

Route::get('/', function () {
    return view('replayer', ['tags' => json_encode(
        ['a' => '2']
    )]);
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::resource('hands', 'HandController');
