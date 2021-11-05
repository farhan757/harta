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

Route::get('/','EmployeeController@index')->name('home');
Route::get('/detail/{nik}','EmployeeController@detail')->name('detail');
Route::get('/form','EmployeeController@form')->name('newform');
Route::get('/formedit/{nik}','EmployeeController@formedit')->name('formedit');
Route::get('/exportexcel','EmployeeController@exportexcel')->name('exportexcel');

Route::get('/getemployee','EmployeeController@getemployee')->name('getemployee');
Route::post('/addemployee','EmployeeController@addemployee')->name('addemployee');
Route::post('/updateemployee','EmployeeController@saveemployee')->name('updateemployee');
Route::get('/delete','EmployeeController@delete')->name('delete');
