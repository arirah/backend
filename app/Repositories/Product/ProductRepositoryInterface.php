<?php


namespace App\Repositories\Product;


use Illuminate\Http\Request;

interface ProductRepositoryInterface
{
    public function addProduct(Request $request);

    public function deleteProduct(Request $request);

    public function getMyProducts();

    public function productById($id);

    public function productList(Request $request);
}
