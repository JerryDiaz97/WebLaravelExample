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
    return view('content/content');
});

Route::get('/category', 'CategoryController@index');
Route::post('/category/register', 'CategoryController@store');
Route::put('/category/update', 'CategoryController@update');
Route::put('/category/activate', 'CategoryController@activate');
Route::put('/category/deactivate', 'CategoryController@deactivate');
Route::get('/category/selectCategory', 'CategoryController@selectCategory');

Route::get('/product', 'ProductController@index');
Route::post('/product/register', 'ProductController@store');
Route::put('/product/update', 'ProductController@update');
Route::put('/product/activate', 'ProductController@activate');
Route::put('/product/deactivate', 'ProductController@deactivate');