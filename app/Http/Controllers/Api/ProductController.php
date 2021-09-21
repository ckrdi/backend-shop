<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        return response()->json([
            'products' => Product::latest()->get(),
            'success' => true,
            'message' => 'Products List'
        ], 200);
    }

    public function show($slug)
    {
        try {
            $product = Product::where('slug', $slug)->first();
        } catch (\Exception $exception) {
            return response()->json([
                'success' => false,
                'message' => $exception->getMessage()
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Show product detail: ' . $product->title,
            'product' => $product
        ], 200);
    }
}
