<?php 
$I = new AcceptanceTester($scenario);
$I->am('A registered active User');
$I->wantTo('Create a new contract');

\Auth::login(\SICV\Users\User::find(1));

$I->amOnPage('/');
$I->seeLink('Nuevo Contrato');
$I->click('Nuevo Contrato');
$I->seeInCurrentUrl('/contract/new');
$I->wait(20);

$I->fillField('client_search', 'Marc');

$I->see('Datos del Contrato');

$I->makeScreenshot('new_contract');

$I->executeJS("$('#client_search').trigger('keyup')");
$I->waitForText("Resultados de Busqueda", 10);
