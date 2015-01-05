<?php namespace SICV\Commander;

interface CommandBus {

    public function execute($command);

}