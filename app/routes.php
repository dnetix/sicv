<?php

App::bind('SICV\Commander\CommandBus', 'SICV\Commander\ValidationCommandBus');

Route::get('/', "HomeController@index");

Route::get('/login', ['as' => 'user.login', 'uses' => 'UserController@login']);
Route::post('/login', ['as' => 'user.login', 'uses' => 'UserController@authenticate']);

Route::group(['before' => 'auth'], function(){
    Route::get('dashboard', ['uses' => 'HomeController@dashboard', 'as' => 'user.dashboard']);
    Route::get('logout', ['uses' => 'UserController@logout', 'as' => 'user.logout']);

    Route::get('client/new', ['uses' => 'ClientController@create', 'as' => 'client.new']);
    Route::post('client/new', ['uses' => 'ClientController@store', 'as' => 'client.store']);

    Route::get('/client/view/{id}', ['uses' => 'ClientController@view', 'as' => 'client.view']);
    Route::post('/client/edit/{id}', ['uses' => 'ClientController@edit', 'as' => 'client.edit']);
    Route::get('/client/profile/{id?}', ['uses' => 'ClientController@profile', 'as' => 'client.profile']);
    Route::get('/client/search', ['uses' => 'ClientController@search', 'as' => 'client.search']);

    Route::get('/contract/view/{id}', ['uses' => 'ContractController@view', 'as' => 'contract.view']);
    Route::get('/contract/new/{client_id?}', ['uses' => 'ContractController@create', 'as' => 'contract.new']);
    Route::post('/contract/new', ['uses' => 'ContractController@store', 'as' => 'contract.store']);
    Route::get('/contract/day', ['uses' => 'ContractController@contractsofday', 'as' => 'contract.day']);
});

Route::get('/preview/{template}', "HomeController@preview");
