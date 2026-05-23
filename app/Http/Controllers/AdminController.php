<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use Carbon\Carbon;

class AdminController extends Controller
{
    public function dashboard()
    {
        /*
        |--------------------------------------------------------------------------
        | TOTAL PRODUK
        |--------------------------------------------------------------------------
        */
        $totalProducts = Product::count();

        /*
        |--------------------------------------------------------------------------
        | TOTAL PESANAN
        |--------------------------------------------------------------------------
        */
        $totalOrders = Order::count();

        /*
        |--------------------------------------------------------------------------
        | STATUS PESANAN
        |--------------------------------------------------------------------------
        */
        $pendingOrders = Order::where('status', Order::STATUS_PENDING)
            ->count();

        $completedOrders = Order::where('status', Order::STATUS_SELESAI)
            ->count();

        /*
        |--------------------------------------------------------------------------
        | TOTAL PENDAPATAN
        |--------------------------------------------------------------------------
        */
        $totalRevenue = Order::where('status', Order::STATUS_SELESAI)
            ->sum('total_price');

        /*
        |--------------------------------------------------------------------------
        | PESANAN HARI INI
        |--------------------------------------------------------------------------
        */
        $todayOrders = Order::whereDate(
            'created_at',
            Carbon::today()
        )->count();

        $todayRevenue = Order::whereDate(
                'created_at',
                Carbon::today()
            )
            ->where('status', Order::STATUS_SELESAI)
            ->sum('total_price');

        /*
        |--------------------------------------------------------------------------
        | STOK HABIS
        |--------------------------------------------------------------------------
        */
        $outOfStock = Product::where('stock', '<=', 0)
            ->count();

        /*
        |--------------------------------------------------------------------------
        | DATA STATUS UNTUK CHART
        |--------------------------------------------------------------------------
        */
        $statusCounts = Order::selectRaw('status, COUNT(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status');

        /*
        |--------------------------------------------------------------------------
        | ORDER TERBARU
        |--------------------------------------------------------------------------
        */
        $latestOrders = Order::latest()
            ->take(5)
            ->get();

        /*
        |--------------------------------------------------------------------------
        | RETURN VIEW
        |--------------------------------------------------------------------------
        */
        return view('admin.dashboard', compact(
            'totalProducts',
            'totalOrders',
            'pendingOrders',
            'completedOrders',
            'totalRevenue',
            'todayOrders',
            'todayRevenue',
            'outOfStock',
            'statusCounts',
            'latestOrders'
        ));
    }
}