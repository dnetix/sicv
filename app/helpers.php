<?php

function public_assets($path_to_file){
    return '/public/'.$path_to_file;
}

function money_to_number($money){
    return preg_replace('/[\$.\s\']/', '', $money);
}