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

Route::group(['middleware' => ['auth'], 'prefix' => 'admin', 'as' => 'admin.'], function () {
    if ( ! request()->ajax() ) {
        Route::get('posts', 'PostsController@index')->name('posts.index');
        Route::get('posts/create', 'PostsController@index')->name('posts.index');
        Route::get('posts/edit/{id}', 'PostsController@index')->name('posts.index');
        Route::get('settings', 'SettingsController@index')->name('settings.index');
        Route::get('settings/create', 'SettingsController@index')->name('settings.index');
        Route::get('settings/edit/{id}', 'SettingsController@index')->name('settings.index');
    }
});

Route::get('/posts', 'PostsController@show')->name('posts.show');
Route::get('/posts/{id}', 'PostsController@show')->name('posts.post');
