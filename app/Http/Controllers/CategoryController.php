<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{

    public function index()
    {
        $categories = Category::query()
            ->limit(10)
            ->select(['id', 'name'])
//            ->orderBy('name')
            ->get();
        $categories->each->append("icon");

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
