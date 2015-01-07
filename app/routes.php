<?php

App::bind('SICV\Commander\CommandBus', 'SICV\Commander\ValidationCommandBus');

Route::get('/', "HomeController@index");

Route::get('/login', ['as' => 'user.login', 'uses' => 'UserController@login']);
Route::post('/login', ['as' => 'user.login', 'uses' => 'UserController@authenticate']);

Route::group(['before' => 'auth'], function(){
    Route::get('/dashboard', ['as' => 'user.dashboard', 'uses' => 'HomeController@dashboard']);
    Route::get('/logout', ['as' => 'user.logout', 'uses' => 'UserController@logout']);

    Route::get('/client/new', ['as' => 'client.new', 'uses' => 'ClientController@create']);
    Route::post('/client/new', ['as' => 'client.store', 'uses' => 'ClientController@store']);

    Route::get('/client/view/{id}', ['uses' => 'ClientController@view', 'as' => 'client.view']);
    Route::post('/client/edit/{id}', ['uses' => 'ClientController@edit', 'as' => 'client.edit']);

});

Route::get('/preview/{template}', "HomeController@preview");
