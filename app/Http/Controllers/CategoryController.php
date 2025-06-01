<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class CategoryController extends Controller
{

    public function index()
    {
        $categories = Cache::rememberForever('categories', function () {
            $cat = Category::query()
                ->select(['id', 'name'])
                ->get();
            $cat->each->append("icon");

            return $cat->toArray();
        });

        $response = [
            'success' => true,
            'message' => 'List of all categories',
            'data' => [
                'categories' => $categories
            ]
        ];
        return response()->json($response);
    }

}
