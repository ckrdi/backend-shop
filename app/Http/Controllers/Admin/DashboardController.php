<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * index
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // invoice count
        $pending = Invoice::where('status', 'pending')->count();
        $success = Invoice::where('status', 'success')->count();
        $failed = Invoice::where('status', 'failed')->count();
        $expired = Invoice::where('status', 'expired')->count();

        // year and month
        $year = date('Y');
        $month = date('m');

        // revenue stats
        $revenueMonth = Invoice::where('status', 'success')
            ->whereMonth('created_at', '=', $month)
            ->whereYear('created_at', $year)
            ->sum('grand_total');
        $revenueYear = Invoice::where('status', 'success')
            ->whereYear('created_at', $year)
            ->sum('grand_total');
        $revenueAll = Invoice::where('status', 'success')
            ->sum('grand_total');

        return view('admin.dashboard.index', compact(
            'pending',
            'success',
            'failed',
            'expired',
            'revenueMonth',
            'revenueYear',
            'revenueAll'
        ));
    }
}