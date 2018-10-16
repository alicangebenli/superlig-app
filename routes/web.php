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

Route::get('/','HomeController@index');
Route::get('/match','HomeController@match')->name('match');
Route::get('/allMatch','HomeController@allMatch')->name('allMatch');
Route::post('/getFTable','HomeController@getFTable')->name('getFTable');
Route::get('/getPTable','HomeController@getPTable')->name('getPTable');