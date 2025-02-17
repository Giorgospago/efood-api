<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
 
    public function index()
    {
        $categories = Category::query()
            ->select(['id', 'name'])
            ->orderBy('name')
            ->get();

        $response = [
            'success' => true,
            'message' => 'List of all categories',
            'categories' => $categories
        ];
        return response()->json($response);
    }

}
