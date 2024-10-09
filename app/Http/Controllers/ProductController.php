<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Services\Interfaces\ProductServiceInterface;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ProductController extends Controller
{
    protected $productService;

    public function __construct(ProductServiceInterface $productService)
    {
        $this->productService = $productService;
    }
    public function index(Request $request)
    {
        $categories = Category::all();
        $products = $this->productService->getAllProducts();

        if ($request->has('pro_name') || $request->has('pro_code')) {
            if ($request->has('pro_name') && $request->pro_name) {
                $products = $products->filter(function ($product) use ($request) {
                    return str_contains($product->pro_name, $request->pro_name);
                });
            }

            if ($request->has('pro_code') && $request->pro_code) {
                $products = $products->filter(function ($product) use ($request) {
                    return str_contains($product->pro_code, $request->pro_code);
                });
            }
        }
        return view('products.index' , compact('categories' , 'products'));
    }

    public function store(Request $request)
    {
        if ($this->productService->existProductByCode($request->pro_code)) {
            return back()->withErrors(['pro_code' => 'El producto ya existe.'])->withInput();
        }
        if ($this->productService->existProductByName($request->pro_name)) {
            return back()->withErrors(['pro_name' => 'El producto con ese nombre ya existe.'])->withInput();
        }

        try {
            $validatedData = $request->validate([
                'pro_name' => 'required|max:255',
                'pro_amount' => 'required',
                'pro_unit_price' => 'required',
                'pro_description' => 'nullable|max:255',
                'pro_image' => 'nullable|image|max:2048',
                'cat_id' => 'required|exists:tbl_category,cat_id',
            ]);

            if ($request->hasFile('pro_image')) {
                $validatedData['pro_image'] = $request->file('pro_image')->store('profile_images', 'public');
            }

            $product = $this->productService->createProduct($validatedData);

            if (!$product) {
                return back()->withErrors(['pro_code' => 'No se pudo crear el producto.'])->withInput();
            }
            return redirect()->route('products.index')->with('success', 'Producto creado con éxito.');
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return back()->withErrors(['pro_code' => 'No se pudo crear el producto.'])->withInput();
        }
    }

    public function edit($id)
    {
        $product = $this->productService->getProductById($id);
        return view('products.edit', compact('product'));
    }

    public function update(Request $request , $id)
    {
        if ($this->productService->existProductByCode($request->pro_code)) {
            return back()->withErrors(['pro_code' => 'El producto ya existe.'])->withInput();
        }
        if ($this->productService->existProductByName($request->pro_name)) {
            return back()->withErrors(['pro_code' => 'El producto con ese nombre ya existe.'])->withInput();
        }

        try {
            $validatedData = $request->validate([
                'pro_code' => 'required|max:10',
                'pro_name' => 'required|max:255',
                'pro_description' => 'nullable|max:255',
                'pro_image' => 'nullable|image|max:2048',
                'pro_unit_price' => 'required',
                'pro_amount' => 'required',
                'cat_id' => 'required|exists:category,cat_id',
            ]);

            $product = $this->productService->updateProduct($id, $validatedData);

            if (!$product) {
                return back()->withErrors(['pro_code' => 'No se pudo actualizar el producto.'])->withInput();
            }
            return redirect()->route('products.index')->with('success', 'Producto actualizado con éxito.');
        } catch (Exception $e) {
            Log::error('Error al crear el producto: ' . $e->getMessage());
            return back()->withErrors(['pro_code' => 'No se pudo actualizar el producto.'])->withInput();
        }
    }

    public function destroy($id)
    {
        $product = $this->productService->deleteProduct($id);
        if (!$product) {
            return back()->withErrors(['pro_code' => 'No se pudo eliminar el producto.'])->withInput();
        }
        return redirect()->route('products.index')->with('success', 'Producto eliminado con plaisio.');
    }
}
