<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    // Display reports
    public function index(Request $request)
    {
        [$from, $to] = $this->dateRange($request);

        $ordersQuery = $this->ordersForPeriod($from, $to);

        $totalOrders = (clone $ordersQuery)->count();

        $pendingOrders = (clone $ordersQuery)
            ->where('status', 'pending')->count();

        $processingOrders = (clone $ordersQuery)
            ->where('status', 'processing')->count();

        $shippedOrders = (clone $ordersQuery)
            ->where('status', 'shipped')->count();

        $deliveredOrders = (clone $ordersQuery)
            ->where('status', 'delivered')->count();

        $cancelledOrders = (clone $ordersQuery)
            ->where('status', 'cancelled')->count();

        $totalOrderValue = (clone $ordersQuery)
            ->where('status', '!=', 'cancelled')
            ->sum('total');

        $totalSales = (clone $ordersQuery)
            ->where('status', 'delivered')
            ->sum('total');

        $bestSellingProducts = $this->bestSellingProducts($from, $to);

        $orders = (clone $ordersQuery)
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('admin.reports.index', compact(
            'from',
            'to',
            'totalOrders',
            'pendingOrders',
            'processingOrders',
            'shippedOrders',
            'deliveredOrders',
            'cancelledOrders',
            'totalOrderValue',
            'totalSales',
            'bestSellingProducts',
            'orders'
        ));
    }

    // Export order details as an Excel-compatible CSV file
    public function exportExcel(Request $request)
    {
        [$from, $to] = $this->dateRange($request);

        $orders = $this->ordersForPeriod($from, $to)
            ->latest();

        $filename = 'luxury-fashion-orders-' . $from . '-to-' . $to . '.csv';

        return response()->streamDownload(function () use ($orders) {

            $file = fopen('php://output', 'w');

            // UTF-8 BOM helps Excel display text correctly
            fwrite($file, "\xEF\xBB\xBF");

            fputcsv($file, [
                'Order Number',
                'Customer Name',
                'Customer Email',
                'Phone',
                'Order Date',
                'Subtotal',
                'Shipping',
                'Total',
                'Payment Method',
                'Payment Status',
                'Order Status',
            ]);

            foreach ($orders->cursor() as $order) {
                fputcsv($file, [
                    $order->order_number,
                    $order->customer_name,
                    $order->customer_email,
                    $order->customer_phone,
                    $order->created_at->format('d-m-Y H:i'),
                    $order->subtotal,
                    $order->shipping_cost,
                    $order->total,
                    $order->payment_method,
                    $order->payment_status,
                    $order->status,
                ]);
            }

            fclose($file);

        }, $filename, [
            'Content-Type' => 'text/csv; charset=UTF-8',
        ]);
    }

    // Display a print-ready report which can be saved as PDF
    public function exportPdf(Request $request)
    {
        [$from, $to] = $this->dateRange($request);

        $orders = $this->ordersForPeriod($from, $to)
            ->latest()
            ->get();

        $totalOrders = $orders->count();

        $totalOrderValue = $orders
            ->where('status', '!=', 'cancelled')
            ->sum('total');

        $totalSales = $orders
            ->where('status', 'delivered')
            ->sum('total');

        $bestSellingProducts = $this->bestSellingProducts($from, $to);

        return view('admin.reports.pdf', compact(
            'from',
            'to',
            'orders',
            'totalOrders',
            'totalOrderValue',
            'totalSales',
            'bestSellingProducts'
        ));
    }

    // Get and validate selected dates
    private function dateRange(Request $request): array
    {
        $validated = $request->validate([
            'from' => ['nullable', 'date'],
            'to' => ['nullable', 'date', 'after_or_equal:from'],
        ]);

        $from = $validated['from'] ?? now()->subDays(29)->toDateString();
        $to = $validated['to'] ?? now()->toDateString();

        return [$from, $to];
    }

    // Reusable query for orders in the selected date range
    private function ordersForPeriod(string $from, string $to)
    {
        return Order::whereDate('created_at', '>=', $from)
            ->whereDate('created_at', '<=', $to);
    }

    // Reusable best-selling products query
    private function bestSellingProducts(string $from, string $to)
    {
        return DB::table('order_items')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->whereDate('orders.created_at', '>=', $from)
            ->whereDate('orders.created_at', '<=', $to)
            ->where('orders.status', '!=', 'cancelled')
            ->select(
                'order_items.product_name',
                DB::raw('SUM(order_items.quantity) as total_sold'),
                DB::raw('SUM(order_items.line_total) as total_revenue')
            )
            ->groupBy('order_items.product_name')
            ->orderByDesc('total_sold')
            ->take(10)
            ->get();
    }
}
