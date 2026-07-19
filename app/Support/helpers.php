<?php

if (! function_exists('money')) {
    /**
     * Format an integer amount of Colombian pesos for display: $ 1.234.567
     */
    function money(int|float|null $amount): string
    {
        return '$ '.number_format((float) ($amount ?? 0), 0, ',', '.');
    }
}
