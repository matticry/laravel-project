<?php

namespace App\Services;

use App\Models\Category;
use App\Services\Interfaces\CategoryServiceInterface;

class CategoryService implements CategoryServiceInterface
{

    /**
     * @return mixed
     */
    public function getAllCategories()
    {
        return Category::all();
    }
}
