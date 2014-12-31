<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the Closure to execute when that URI is requested.
|
*/

Route::get('/', "HomeController@index");
Route::get('/login', [
    'as' => 'user.login',
    'uses' => 'UserController@login'
]);

Route::post('/login', [
    'as' => 'user.login',
    'uses' => 'UserController@authenticate'
]);

Route::group(['before' => 'auth'], function(){

    Route::get('/dashboard', ['as' => 'user.dashboard', 'uses' => 'HomeController@dashboard']);
    Route::get('/logout', [
        'as' => 'user.logout',
        'uses' => 'UserController@logout'
    ]);
});

Route::get('/preview/{template}', "HomeController@preview");
