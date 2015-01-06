<?php 
$I = new FunctionalTester($scenario);

$I->am('A logged active user');
$I->wantTo('create a new client in the application');

$I->amOnPage('/dashboard');

$I->click('Nuevo Cliente');

$I->seeInCurrentUrl('client/new');
$I->see('Nuevo cliente');

$info = [
    'name' => 'Diego Arturo Calle',
    'id_type' => 'CC',
    'id_number' => '1040035062',
    'id_expedition' => 'La Ceja',
    'cell_number' => '3006108399',
    'phone_number' => '3006108399',
    'address' => '3006108399'
];

$I->fillField('name', $info['name']);
$I->selectOption('id_type', $info['id_type']);
$I->fillField('id_number', $info['id_number']);
$I->fillField('id_expedition', $info['id_expedition']);
$I->fillField('cell_number', $info['cell_number']);
$I->fillField('phone_number', $info['phone_number']);
$I->fillField('address', $info['address']);

$I->click('Guardar Cliente');

$I->seeRecord('clients', $info);