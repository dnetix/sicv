<?php

namespace App\Models\Sales;

class SalesRepository
{
    public function saveProduct(Product &$product)
    {
        $product->save();
    }
}
