<?php


namespace App\Repositories\Product;

use App\Products;
use Illuminate\Http\Request;

class ProductRepository implements ProductRepositoryInterface
{
    /**
     * @return mixed
     */
    public function addProduct(Request $request)
    {
        $req = $request->only('title', 'description', 'price', 'image');
        if ($request->id) {
            \Auth::user()->products()->findOrFail($request->id)->update($req);
            return response()->json($this->getProducts(), 200);
        }
        $req['user_id'] = \Auth::user()->id;
        Products::create($req);
        return response()->json($this->getProducts(), 200);
    }

    /**
     * @param $id
     * @return mixed
     */
    public function deleteProduct(Request $request)
    {
        \DB::beginTransaction();
        try {
            \Auth::user()->products()->findOrFail($request->id)->delete();
        } catch (\Exception $e) {
            \DB::rollBack();
        }
        \DB::commit();
        return response()->json($this->getProducts(), 200);
    }

    /**
     * @return mixed
     */
    public function getMyProducts()
    {
        return response()->json($this->getProducts(), 200);
    }


    /**
     * @param $id
     * @return mixed
     */
    public function productById($id)
    {
        $product = (new Products)->findOrFail($id);
        return response()->json($product, 200);
    }

    private function getProducts()
    {
        return \Auth::user()->products()->orderBy('id', 'desc')->paginate(5);
    }

    public function productList(Request $request)
    {
        $products = (new Products);
        return response()->json($products->orderBy('id', 'desc')->paginate(10), 200);
    }
}
