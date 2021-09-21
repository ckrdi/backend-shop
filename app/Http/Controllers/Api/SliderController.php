<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Slider;

class SliderController extends Controller
{
    public function index()
    {
        try {
            $sliders = Slider::latest()->get();
        } catch (\Exception $exception) {
            return response()->json([
                'success'   => false,
                'message'   => $exception->getMessage(),
            ]);
        }

        return response()->json([
            'success'   => true,
            'message'   => 'Sliders List',
            'sliders'   => $sliders
        ]);
    }
}
