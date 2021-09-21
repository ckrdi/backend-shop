<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function index()
    {
        $invoices = Invoice::where('customer_id', auth()->user()->id)->latest()->get();

        return response()->json([
            'success' => true,
            'message' => 'Invoices List: '. auth()->user()->name,
            'data'    => $invoices
        ], 200);
    }

    public function show($snap_token)
    {
        $invoice = Invoice::where('customer_id', auth()->user()->id)
            ->where('snap_token', $snap_token)
            ->latest()
            ->first();

        return response()->json([
            'success' => true,
            'message' => 'Invoices Detail: '. auth()->user()->name,
            'data'    => $invoice,
            'product' => $invoice->orders
        ], 200);
    }
}
