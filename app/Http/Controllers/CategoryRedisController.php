<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Support\Facades\Redis;

class CategoryRedisController extends Controller
{
    public function index()
    {
        $categories = Redis::get('categories');

        if (!$categories)
        {
            $categories = Category::all();

            Redis::set('categories', json_encode($categories));

        } else {
            $categories = json_decode($categories, true);
        }

        return view('redis.index', compact('categories'));
    }
}
