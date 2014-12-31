<?php 
$I = new FunctionalTester($scenario);

// Por defecto las pruebas funcionales deshabilitan los filtros?!
\Route::enableFilters();

$I->am('An active SICV User');
$I->wantTo('Sign in and Sign Out into the application');

$I->amOnPage('/');

$I->fillField('username', 'admin');
$I->fillField('password', 'admin');
$I->click('Acceder');

$I->seeInCurrentUrl('/dashboard');
$I->see('Panel Principal');

$I->assertTrue(Auth::check(), 'User has logged into the session');

$I->click('Cerrar Sesión');
$I->seeInCurrentUrl('/login');

$I->assertFalse(Auth::check(), 'User has logged out of the session');