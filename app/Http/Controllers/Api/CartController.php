<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function index(): \Illuminate\Http\JsonResponse
    {
        return response()->json([
            'success' => true,
            'message' => 'Cart List',
            'cart'    => Cart::with('product')
                ->where('customer_id', auth()->user()->id)
                ->orderBy('created_at', 'desc')
                ->get()
        ]);
    }

    public function store(Request $request): \Illuminate\Http\JsonResponse
    {
        // find the item in the cart for checking
        $item = Cart::where('product_id', $request->product_id)
            ->where('customer_id', auth()->user()->id)
            ->first();

        // check if there is a same item in the cart
        if ($item) {
            // increment item quantity in the cart
            $item->increment('quantity');

            // sum price of items
            $price = $request->price * $item->quantity;

            // sum weight of items
            $weight = $request->weight * $item->quantity;

            // update the items price and weight in the cart
            $item->update([
                'price' => $price,
                'weight'=> $weight
            ]);
        } else {
            // if there's none of said item, make new
            $item = Cart::create([
                'product_id'    => $request->product_id,
                'customer_id'   => auth()->user()->id,
                'quantity'      => $request->quantity,
                'price'         => $request->price,
                'weight'        => $request->weight
            ]);
        }

        return response()->json([
            'success'   => true,
            'message'   => 'Successfully added to cart',
            'quantity'  => $item->quantity,
            'product'   => $item->product
        ]);
    }

    public function getCartTotalPrice(): \Illuminate\Http\JsonResponse
    {
        $carts = Cart::with('product')
            ->where('customer_id', auth()->user()->id)
            ->orderBy('created_at', 'desc')
            ->sum('price');

        return response()->json([
            'success' => true,
            'message' => 'Total cart price',
            'total'   => $carts
        ]);
    }

    public function getCartTotalWeight(): \Illuminate\Http\JsonResponse
    {
        $carts = Cart::with('product')
            ->where('customer_id', auth()->user()->id)
            ->orderBy('created_at', 'desc')
            ->sum('weight');

        return response()->json([
            'success' => true,
            'message' => 'Total cart weight',
            'total'   => $carts
        ]);
    }

    public function removeCart(Request $request): \Illuminate\Http\JsonResponse
    {
        Cart::with('product')
            ->whereId($request->cart_id)
            ->delete();

        return response()->json([
            'success' => true,
            'message' => 'Cart item removed',
        ]);
    }

    public function removeAllCart(Request $request): \Illuminate\Http\JsonResponse
    {
        Cart::with('product')
            ->where('customer_id', auth()->user()->id)
            ->delete();

        return response()->json([
            'success' => true,
            'message' => 'Remove all items in cart',
        ]);
    }
}
