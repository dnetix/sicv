<?php namespace SICV\Core\Commander;

interface CommandBus {

    public function execute($command);

}