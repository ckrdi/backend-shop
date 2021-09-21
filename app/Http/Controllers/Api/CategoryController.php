<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index()
    {
        return response()->json([
            'categories' => Category::latest()->get(),
            'success' => true,
            'message' => 'Categories List'
        ], 200);
    }

    public function show($slug)
    {
        try {
            $category = Category::with('products')->where('slug', $slug)->first();
        } catch (\Exception $exception) {
            return response()->json([
                'success' => false,
                'message' => $exception->getMessage(),
            ], '404');
        }

        return response()->json([
            'success' => true,
            'message' => 'List product by category: ' . $category->name,
            'product' => $category->products()->latest()->get()
        ], 200);
    }

    public function categoryHeader(): \Illuminate\Http\JsonResponse
    {
        return response()->json([
            'success' => true,
            'message' => 'Category Header Data List',
            'categories' => Category::latest()->take(5)->get()
        ], 200);
    }
}
