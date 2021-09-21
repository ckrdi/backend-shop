<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::latest()->when(\request()->q, function ($categories) {
            $categories->where('name', 'like', '%' . \request()->q . '%');
        })->paginate(10);

        return view('admin.category.index', compact('categories'));
    }

    public function create()
    {
        return view('admin.category.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpeg,jpg,png|max:2000',
            'name' => 'required|unique:categories'
        ]);

        $image = $request->file('image');
        $imageHash = $image->hashName();
        $image->storeAs('public/categories', $imageHash);

        try {
            Category::create([
                'image' => $imageHash,
                'name' => $request->name,
                'slug' => Str::slug($request->name, '-')
            ]);
        } catch (\Exception $exception) {
            return redirect()->route('admin.category.index')->with([
                'error' => 'Data gagal tersimpan',
                'message' => $exception->getMessage()
            ]);
        }

        return redirect()
            ->route('admin.category.index')
            ->with(['success' => 'Data berhasil tersimpan.']);
    }

    public function edit(Category $category)
    {
        return view('admin.category.edit', compact('category'));
    }

    /**
     * @throws \Throwable
     */
    public function update(Request $request, Category $category)
    {
        $request->validate([
            'image' => 'image|mimes:jpeg,jpg,png|max:2000',
            'name' => 'required|unique:categories,name,'. $category->id
        ]);

        if (!$request->hasFile('image')) {
            // update data without image
            $category->updateOrFail([
                'name' => $request->name,
                'slug' => Str::slug($request->name, '-')
            ]);

            return redirect()
                ->route('admin.category.index')
                ->with(['success' => 'Data berhasil tersimpan.']);
        }

        $imagePath = explode('categories/', $category->image);
        Storage::disk('local')->delete('public/categories/' . $imagePath[1]);

        $image = $request->file('image');
        $imageHash = $image->hashName();
        $image->storeAs('public/categories', $imageHash);

        // update data with image
        $category->updateOrFail([
            'image' => $imageHash,
            'name' => $request->name,
            'slug' => Str::slug($request->name, '-')
        ]);

        return redirect()
            ->route('admin.category.index')
            ->with(['success' => 'Data berhasil tersimpan.']);
    }

    public function destroy(Category $category)
    {
        $imagePath = explode('categories/', $category->image);

        try {
            Storage::disk('local')->delete('public/categories/' . $imagePath[1]);
            $category->delete();
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
