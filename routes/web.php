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

Route::get('/', 'DashboardController@home');

Route::get('/signin', 'DashboardController@signin');
Route::get('/register', 'DashboardController@register');
Route::get('/needsignin','DashboardController@needsignin');
Route::get('/signout','DashboardController@signout');

Route::post('/signincheck','DashboardController@signincheck');
Route::post('/insertuser','DashboardController@insertuser');

Route::get('/dashboard', 'DashboardController@dashboard');
Route::get('/mypoll', 'DashboardController@mypoll');
Route::get('/addpoll', 'DashboardController@addpoll');
Route::get('/closepoll/{id}', 'DashboardController@closepoll');
Route::get('/openpoll/{id}', 'DashboardController@openpoll');
Route::get('/vote/{id}', 'DashboardController@vote');
Route::get('/detail/{id}', 'DashboardController@detail');
Route::get('/changepass', 'DashboardController@changepass');

Route::post('/insertvote', 'DashboardController@insertvote');
Route::post('/insertpoll','DashboardController@insertpoll');
Route::post('/updatepass', 'DashboardController@updatepass');
