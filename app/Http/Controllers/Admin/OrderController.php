<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index()
    {
        $invoices = Invoice::latest()->when(\request()->q, function ($invoices) {
            $invoices->where('name', 'like', '%' . \request()->q . '%');
        })->paginate(10);

        return view('admin.order.index', compact('invoices'));
    }

    public function show($id)
    {
        $invoice = Invoice::findOrFail($id);

        return view('admin.order.show', compact('invoice'));
    }
}
