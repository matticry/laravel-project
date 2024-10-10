<?php

namespace App\Http\Controllers;

use App\Services\Interfaces\CategoryServiceInterface;
use Illuminate\Http\Request;

use App\Models\Category;

class CategoryController extends Controller
{
    protected $categoryService;

    public function __construct(CategoryServiceInterface $categoryService)
    {
        $this->categoryService = $categoryService;
    }
    public function index(Request $request)
    {
        $categories = $this->categoryService->getAllCategories();
        if ($request->has('cat_name')) {
            if ($request->has('cat_name') && $request->cat_name) {
                $categories = $categories->filter(function ($category) use ($request) {
                    return str_contains($category->cat_name, $request->cat_name);
                });
            }
        }

        return view('categories.index', compact('categories'));
    }


    public function store(Request $request)
    {

        $request->validate([
            'cat_name' => 'required',
            'cat_description' => 'required',
        ]);

        Category::create($request->all());
        return redirect()->route('categories.index')->with('success', 'Categoría creada.');
    }

    public function edit(Category $category)
    {
        return view('categories.edit', compact('category'));
    }

    public function update(Request $request, Category $category)
    {
        $request->validate([
            'cat_name' => 'required',
            'cat_description' => 'required',
        ]);

        $category->update($request->all());
        return redirect()->route('categories.index')->with('success', 'Categoría actualizada.');
    }

    public function destroy(Category $category)
    {
        $category->delete();
        return redirect()->route('categories.index')->with('success', 'Categoría eliminada.');
    }
}
