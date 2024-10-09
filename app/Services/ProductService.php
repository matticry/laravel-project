<?php

namespace App\Services;

use App\Models\Product;
use App\Services\Interfaces\ProductServiceInterface;
use Illuminate\Database\Eloquent\Collection;
use LaravelIdea\Helper\App\Models\_IH_Product_C;

class ProductService implements ProductServiceInterface
{
    /**
     * @return _IH_Product_C|Collection|Product[]
     */
    public function getAllProducts()
    {
        return Product::all();
    }

    /**
     * @param $id
     * @return _IH_Product_C|Product|Product[]
     */
    public function getProductById($id)
    {
        return Product::findOrFail($id);
    }

    /**
     * @param array $data
     * @return mixed
     */
    public function createProduct(array $data)
    {
        return Product::create($data);
    }

    /**
     * @param $id
     * @param array $data
     * @return _IH_Product_C|Product|Product[]
     */
    public function updateProduct($id, array $data)
    {
       $product = Product::findOrFail($id);
       $product->update($data);
       return $product;
    }

    /**
     * @param $id
     * @return bool|null
     */
    public function deleteProduct($id): ?bool
    {
        $product = Product::findOrFail($id);
        return $product->delete();
    }

    public function existProductByCode($code): bool
    {
        return Product::where('pro_code', $code)->exists();
    }

    public function existProductByName($code): bool
    {
        return Product::where('pro_name', $code)->exists();
    }


}
