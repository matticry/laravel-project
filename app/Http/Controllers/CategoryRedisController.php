<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Exception;
use Illuminate\Support\Facades\Redis;

class CategoryRedisController extends Controller
{
    public function index()
    {
        try {
            $categories = Redis::get('categories');

            if (!$categories)
            {
                $categories = Category::all();

                Redis::set('categories', json_encode($categories));

            } else {
                $categories = json_decode($categories);
            }

            return view('redis.index', compact('categories'));
        }catch (Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());

        }
    }
}
