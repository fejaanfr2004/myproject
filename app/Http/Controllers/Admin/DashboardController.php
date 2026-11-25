<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Order;
use App\Models\Product;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // User statistics
        $totalUsers = User::count();
        $totalAdmins = User::role('Super Admin')->count();
        $totalCustomers = User::role('Customer')->count();
        $totalRoles = Role::count();

        // Product statistics
        $totalProducts = Product::count();

        // Order statistics
        $totalOrders = Order::count();

        $pendingOrders = Order::where('status', 'pending')->count();
        $processingOrders = Order::where('status', 'processing')->count();
        $shippedOrders = Order::where('status', 'shipped')->count();
        $deliveredOrders = Order::where('status', 'delivered')->count();
        $cancelledOrders = Order::where('status', 'cancelled')->count();

        // Order value excluding cancelled orders
        $totalOrderValue = Order::where('status', '!=', 'cancelled')
            ->sum('total');

        // Completed sales from delivered orders
        $deliveredSales = Order::where('status', 'delivered')
            ->sum('total');

        // Orders placed today
        $todayOrders = Order::whereDate('created_at', today())->count();

        // Latest five orders
        $recentOrders = Order::latest()
            ->take(5)
            ->get();

        // Best-selling products (excluding cancelled orders)
        $bestSellingProducts = DB::table('order_items')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->where('orders.status', '!=', 'cancelled')
            ->select(
                'order_items.product_name',
                DB::raw('SUM(order_items.quantity) as total_sold'),
                DB::raw('SUM(order_items.line_total) as total_revenue')
            )
            ->groupBy('order_items.product_name')
            ->orderByDesc('total_sold')
            ->take(5)
            ->get();

        // Monthly delivered sales for the last six months
        $monthlySales = [];

        $firstMonth = Carbon::now()->startOfMonth()->subMonths(5);

        for ($i = 0; $i < 6; $i++) {
            $month = $firstMonth->copy()->addMonths($i);

            $sales = Order::where('status', 'delivered')
                ->whereBetween('created_at', [
                    $month->copy()->startOfMonth(),
                    $month->copy()->endOfMonth()
                ])
                ->sum('total');

            $monthlySales[] = [
                'month' => $month->format('M Y'),
                'sales' => (float) $sales,
            ];
        }

        return view('admin.dashboard', compact(
            'totalUsers',
            'totalAdmins',
            'totalCustomers',
            'totalRoles',
            'totalProducts',
            'totalOrders',
            'pendingOrders',
            'processingOrders',
            'shippedOrders',
            'deliveredOrders',
            'cancelledOrders',
            'totalOrderValue',
            'deliveredSales',
            'todayOrders',
            'recentOrders',
            'bestSellingProducts',
            'monthlySales'
        ));
    }
}
