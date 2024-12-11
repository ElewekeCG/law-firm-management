<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class Dashboard extends Controller
{
    public function showDashboard()
    {
        $totalOrders = OrderModel::count();
        $totalSales = OrderModel::sum('total');
        $totalProducts = Product::count();
        $totalCustomers = CustomerModel::count();

        $salesTrend = OrderModel::select(
            DB::raw('DATE(created_at) as date'),
            DB::raw('SUM(total) as total_sales')
        )
            ->groupBy('date')
            ->orderBy('date')
            ->take(7)  // Last 7 days for simplicity
            ->get();

            $topSellingProducts = OrderModel::select('productId', DB::raw('SUM(quantity) as total_sold'))
            ->with('product:id,name')
            ->groupBy('ProductId')
            ->orderByDesc('total_sold')
            ->take(5)
            ->get();

            $recentOrders = OrderModel::with('customer:id,name')
            ->latest()
            ->take(10)
            ->get();

            $lowStockThreshold = 10;
            $lowStockProducts = Product::where('quantity', '<=', $lowStockThreshold)
            ->get();

        return view('admin.dashboard', compact(
            'totalOrders',
            'totalSales',
            'totalProducts',
            'salesTrend',
            'totalCustomers',
            'topSellingProducts',
            'recentOrders',
            'lowStockProducts'
        ));
    }
}
