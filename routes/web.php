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
Route::group(['middleware' => ['guest']], function () {
    Route::get('/', 'Auth\LoginController@showLoginForm');
    Route::post('/login', 'Auth\LoginController@login')->name('login');
});
Route::group(['middleware' => ['auth']], function () {

    Route::post('/logout', 'Auth\LoginController@logout')->name('logout');

    Route::get('/main', function () {
        return view('content/content');
    })->name('main');

    Route::group(['middleware' => ['Almacenista']], function () {

        Route::get('/category', 'CategoryController@index');
        Route::post('/category/register', 'CategoryController@store');
        Route::put('/category/update', 'CategoryController@update');
        Route::put('/category/activate', 'CategoryController@activate');
        Route::put('/category/deactivate', 'CategoryController@deactivate');
        Route::get('/category/selectCategory', 'CategoryController@selectCategory');

        Route::get('/entry', 'EntryController@index');
        Route::post('/entry/register', 'EntryController@store');
        Route::put('/entry/deactivate', 'EntryController@deactivate');

        Route::get('/product', 'ProductController@index');
        Route::post('/product/register', 'ProductController@store');
        Route::put('/product/update', 'ProductController@update');
        Route::put('/product/activate', 'ProductController@activate');
        Route::put('/product/deactivate', 'ProductController@deactivate');

        Route::get('/provider', 'ProviderController@index');
        Route::post('/provider/register', 'ProviderController@store');
        Route::put('/provider/update', 'ProviderController@update');
        Route::get('/provider/selectProvider', 'ProviderController@selectProvider');
        
    });

    Route::group(['middleware' => ['Vendedor']], function () {
        Route::get('/client', 'ClientController@index');
        Route::post('/client/register', 'ClientController@store');
        Route::put('/client/update', 'ClientController@update');
    });
    
    Route::group(['middleware' => ['Administrador']], function () {
        Route::get('/category', 'CategoryController@index');
        Route::post('/category/register', 'CategoryController@store');
        Route::put('/category/update', 'CategoryController@update');
        Route::put('/category/activate', 'CategoryController@activate');
        Route::put('/category/deactivate', 'CategoryController@deactivate');
        Route::get('/category/selectCategory', 'CategoryController@selectCategory');

        Route::get('/entry', 'EntryController@index');
        Route::post('/entry/register', 'EntryController@store');
        Route::put('/entry/deactivate', 'EntryController@deactivate');

        Route::get('/product', 'ProductController@index');
        Route::post('/product/register', 'ProductController@store');
        Route::put('/product/update', 'ProductController@update');
        Route::put('/product/activate', 'ProductController@activate');
        Route::put('/product/deactivate', 'ProductController@deactivate');

        Route::get('/provider', 'ProviderController@index');
        Route::post('/provider/register', 'ProviderController@store');
        Route::put('/provider/update', 'ProviderController@update');
        Route::get('/provider/selectProvider', 'ProviderController@selectProvider');

        Route::get('/client', 'ClientController@index');
        Route::post('/client/register', 'ClientController@store');
        Route::put('/client/update', 'ClientController@update');

        Route::get('/role', 'RoleController@index');
        Route::get('/role/selectRole', 'RoleController@selectRole');
        
        Route::get('/user', 'UserController@index');
        Route::post('/user/register', 'UserController@store');
        Route::put('/user/update', 'UserController@update');
        Route::put('/user/activate', 'UserController@activate');
        Route::put('/user/deactivate', 'UserController@deactivate');

    });   
    
});

//Route::get('/home', 'HomeController@index')->name('home');
