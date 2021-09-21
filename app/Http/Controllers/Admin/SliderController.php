<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Slider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SliderController extends Controller
{
    public function index()
    {
        return view('admin.slider.index', [
            'sliders' => Slider::latest()->paginate(5)
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpeg,jpg,png|max:2000',
            'link' => 'required'
        ]);

        $image = $request->file('image');
        $imgHash = $image->hashName();
        $image->storeAs('public/sliders', $imgHash);

        try {
            Slider::create([
                'image' => $imgHash,
                'link' => $request->link
            ]);
        } catch (\Exception $exception) {
            return redirect()->route('admin.slider.index')->with([
                'error' => 'Data gagal tersimpan',
                'message' => $exception->getMessage()
            ]);
        }

        return redirect()->route('admin.slider.index')->with([
            'success' => 'Data berhasil tersimpan'
        ]);
    }

    public function destroy($id)
    {
        $slider = Slider::findOrFail($id);

        $imgPath = explode('sliders/', $slider->image);

        try {
            Storage::disk('local')->delete('public/sliders/' . $imgPath[1]);
            $slider->delete();
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
