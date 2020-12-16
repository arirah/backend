<?php


namespace App\Http\Controllers;

use App\Repositories\Product\ProductRepositoryInterface;
use Illuminate\Http\Request;

class HomeController
{

    private $products;

    /**
     * HomeController constructor.
     * @param ProductRepositoryInterface $products
     */
    public function __construct(ProductRepositoryInterface $products)
    {
        $this->products = $products;

    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function productList(Request $request)
    {
        return $this->products->productList($request);
    }

    /**
     * @param null $id
     * @return mixed
     */
    public function productDetails($id = null)
    {
        return $this->products->productById($id);
    }
}
