<?php  namespace SICV\Sales;

class SalesRepository {

    public function saveProduct(Product &$product){
        $product->save();
    }

}