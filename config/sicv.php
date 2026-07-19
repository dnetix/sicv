<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Client search
    |--------------------------------------------------------------------------
    |
    | Maximum number of clients returned by the live search endpoints
    | (dashboard card, contract form, POS).
    |
    */

    'client_search_limit' => (int) env('CLIENT_SEARCH_LIMIT', 15),

];
