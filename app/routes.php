<?php

App::bind('SICV\Core\Commander\CommandBus', 'SICV\Core\Commander\ValidationCommandBus');

Route::get('/', "HomeController@index");

Route::get('/login', ['as' => 'user.login', 'uses' => 'UserController@login']);
Route::post('/login', ['as' => 'user.login', 'uses' => 'UserController@authenticate']);

Route::group(['before' => 'auth'], function(){
    Route::get('dashboard', ['uses' => 'HomeController@dashboard', 'as' => 'user.dashboard']);
    Route::get('logout', ['uses' => 'UserController@logout', 'as' => 'user.logout']);
    Route::post('search', ['uses' => 'HomeController@search', 'as' => 'user.search']);
    Route::get('/goldprice', ['uses' => 'HomeController@goldprice', 'as' => 'goldprice']);

    Route::get('client/new', ['uses' => 'ClientController@create', 'as' => 'client.new']);
    Route::post('client/new', ['uses' => 'ClientController@store', 'as' => 'client.store']);

    Route::get('/client/view/{id}', ['uses' => 'ClientController@view', 'as' => 'client.view']);
    Route::post('/client/edit/{id?}', ['uses' => 'ClientController@edit', 'as' => 'client.edit']);
    Route::get('/client/profile/{id?}', ['uses' => 'ClientController@profile', 'as' => 'client.profile']);
    Route::get('/client/search', ['uses' => 'ClientController@search', 'as' => 'client.search']);
    Route::post('/client/flag', ['uses' => 'ClientController@toggleFlag', 'as' => 'client.toggleflag']);
    Route::post('/client/note', ['uses' => 'ClientController@note', 'as' => 'client.note']);
    Route::get('/client/notes', ['uses' => 'ClientController@notes', 'as' => 'client.notes']);

    Route::get('/contract/view/{id}', ['uses' => 'ContractController@view', 'as' => 'contract.view']);
    Route::get('/contract/new/{client_id?}', ['uses' => 'ContractController@create', 'as' => 'contract.new']);
    Route::post('/contract/new', ['uses' => 'ContractController@store', 'as' => 'contract.store']);
    Route::get('/contract/clone/{id}', ['uses' => 'ContractController@copy', 'as' => 'contract.clone']);
    Route::get('/contract/day', ['uses' => 'ContractController@contractsofday', 'as' => 'contract.day']);
    Route::post('/contract/extension', ['uses' => 'ContractController@extension', 'as' => 'contract.extension']);
    Route::post('/contract/terminate', ['uses' => 'ContractController@terminate', 'as' => 'contract.terminate']);
    Route::post('/contract/annul/{id?}', ['uses' => 'ContractController@annul', 'as' => 'contract.annul']);

    Route::post('/sellout/presellout/{id?}', ['uses' => 'SelloutController@presellout', 'as' => 'contract.presellout']);
    Route::get('/sellout/presellouts', ['uses' => 'SelloutController@presellouts', 'as' => 'sellout.presellouts']);
    Route::get('/sellout/process', ['uses' => 'SelloutController@process', 'as' => 'sellout.process']);
    Route::post('/sellout/create', ['uses' => 'SelloutController@create', 'as' => 'sellout.create']);
    Route::get('/sellout/view/{id}', ['uses' => 'SelloutController@view', 'as' => 'sellout.view']);

    Route::post('/article/location/{id?}', ['uses' => 'ArticleController@updateLocation', 'as' => 'article.location']);
    Route::get('/article/types', ['uses' => 'ArticleController@articletypes', 'as' => 'article.types']);
    Route::get('/article/type/{id?}', ['uses' => 'ArticleController@articletype', 'as' => 'article.type']);
    Route::post('/article/type', ['uses' => 'ArticleController@createOrUpdateArticleType', 'as' => 'article.savetype']);

    Route::get('/report/expiredcontracts', ['uses' => 'ReportController@expiredcontracts', 'as' => 'report.expiredcontracts']);
    Route::get('/report/contractstatistics/{kind}', ['uses' => 'ReportController@contractstatistics', 'as' => 'report.contractstatistics']);
    Route::get('/report/financial', ['uses' => 'ReportController@financial', 'as' => 'report.financial']);

    Route::get('/budget/expenses', ['uses' => 'BudgetController@expenses', 'as' => 'budget.expenses']);
    Route::post('/buget/expense/store', ['uses' => 'BudgetController@storeExpense', 'as' => 'budget.newexpense']);
    Route::get('/budget/expensetype', ['uses' => 'BudgetController@expenseType', 'as' => 'budget.expensetype']);
    Route::get('/budget/expensetypes', ['uses' => 'BudgetController@expenseTypes', 'as' => 'budget.expensetypes']);
    Route::post('/budget/store/expensetype', ['uses' => 'BudgetController@storeExpenseType', 'as' => 'budget.saveexpensetype']);

});

Route::get('/preview/{template}', "HomeController@preview");
