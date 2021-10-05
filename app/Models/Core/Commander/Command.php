<?php

namespace App\Models\Core\Commander;

class Command
{
    protected function normalizeAmount($amount)
    {
        return preg_replace('/[\s\$\'\.\,]/', '', $amount);
    }
}
