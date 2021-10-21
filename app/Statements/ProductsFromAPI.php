<?php
namespace App\Statements;

use OpenFoodFacts\Laravel\Facades\OpenFoodFacts;

class ProductsFromAPI {

    public static function getAPIProductsByName(String $search)
    {
        return OpenFoodFacts::find($search);
    }

    public static function getAPIProductByBarcode(String $barcode)
    {
        return OpenFoodFacts::barcode($barcode);
    }

    public static function getAPIProductCategory(String $barcode)
    {
        return isset(OpenFoodFacts::barcode($barcode)['categories']) ? OpenFoodFacts::barcode($barcode)['categories'] : ConstProductCategory::constProductCategory()[0];
    }
}