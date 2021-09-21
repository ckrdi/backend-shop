<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $products = Product::latest()->when(\request()->q, function ($products) {
            $products->where('name', 'like', '%' . \request()->q . '%');
        })->paginate(10);

        return view('admin.product.index', compact('products'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('admin.product.create', [
            'categories' => Category::latest()->get()
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $request->validate([
            'image' => ['required', 'image', 'mimes:jpeg,jpg,png', 'max:2000'],
            'title' => 'required|unique:products',
            'category_id' => 'required',
            'description' => 'required',
            'weight' => 'required|numeric',
            'price' => 'required|numeric',
            'discount' => 'required|numeric'
        ]);

        $image = $request->file('image');
        $imageHash = $image->hashName();
        $image->storeAs('public/products', $imageHash);

        try {
            Product::create([
                'image' => $imageHash,
                'title' => $request->title,
                'slug' => Str::slug($request->title, '-'),
                'category_id' => $request->category_id,
                'description' => $request->description,
                'weight' => $request->weight,
                'price' => $request->price,
                'discount' => $request->discount
            ]);
        } catch (\Exception $exception) {
            return redirect()->route('admin.product.index')->with([
                'error' => 'Data gagal tersimpan',
                'message' => $exception->getMessage()
            ]);
        }

        return redirect()
            ->route('admin.product.index')
            ->with([ 'success' => 'Data berhasil tersimpan.' ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function show(Product $product)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\View\View
     */
    public function edit(Product $product)
    {
        return view('admin.product.edit', [
            'product' => $product,
            'categories' => Category::latest()->get()
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Product $product
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Throwable
     */
    public function update(Request $request, Product $product)
    {
        $request->validate([
            'image' => ['image', 'mimes:jpeg,jpg,png', 'max:2000'],
            'title' => 'required|unique:products',
            'category_id' => 'required',
            'description' => 'required',
            'weight' => 'required|numeric',
            'price' => 'required|numeric',
            'discount' => 'required|numeric'
        ]);

        if (!$request->hasFile('image')) {
            // update data without image
            $product->updateOrFail([
                'title' => $request->title,
                'slug' => Str::slug($request->title, '-'),
                'category_id' => $request->category_id,
                'description' => $request->description,
                'weight' => $request->weight,
                'price' => $request->price,
                'discount' => $request->discount
            ]);

            return redirect()
                ->route('admin.product.index')
                ->with([ 'success' => 'Data berhasil tersimpan.' ]);
        }

        $imagePath = explode('products/', $product->image);
        Storage::disk('local')->delete('public/products/' . $imagePath[1]);

        $image = $request->file('image');
        $imageHash = $image->hashName();
        $image->storeAs('public/products', $imageHash);

        // update data with image
        $product->updateOrFail([
            'image' => $imageHash,
            'title' => $request->title,
            'slug' => Str::slug($request->title, '-'),
            'category_id' => $request->category_id,
            'description' => $request->description,
            'weight' => $request->weight,
            'price' => $request->price,
            'discount' => $request->discount
        ]);

        return redirect()
            ->route('admin.category.index')
            ->with([ 'success' => 'Data berhasil tersimpan.' ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Product $product)
    {
        $imagePath = explode('products/', $product->image);

        try {
            Storage::disk('local')->delete('public/products/' . $imagePath[1]);
            $product->delete();
        } catch (\Exception $exception) {
            return response()->json([
                'status' => 'failed',
                'message' => $exception->getMessage()
            ]);
        }

        return response()->json([
            'status' => 'success'
        ]);
    }
}
