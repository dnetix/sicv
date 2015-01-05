<?php
namespace Codeception\Module;

// here you can define custom actions
// all public methods declared in helper class will be available in $I

class FunctionalHelper extends \Codeception\Module
{

    public function fillLogin(){
        $I = $this->getModule('Laravel4');
        $I->amOnPage('/');

        $I->fillField('username', 'admin');
        $I->fillField('password', 'admin');
        $I->click('Acceder');
    }

}
