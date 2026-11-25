<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Support\ActivityLogger;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    // Display all orders
    public function index(Request $request)
    {
        $query = Order::with('user')->latest();

        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->where('order_number', 'like', "%{$search}%")
                    ->orWhere('customer_name', 'like', "%{$search}%")
                    ->orWhere('customer_email', 'like', "%{$search}%")
                    ->orWhere('customer_phone', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $orders = $query->paginate(10)->withQueryString();

        return view('admin.orders.index', compact('orders'));
    }

    // Display one order
    public function show(Order $order)
    {
        $order->load('items', 'user');

        return view('admin.orders.show', compact('order'));
    }

    // Update order status
    public function updateStatus(Request $request, Order $order)
    {
        $validated = $request->validate([
            'status' => [
                'required',
                'in:pending,processing,shipped,delivered,cancelled'
            ],
        ]);

        $oldStatus = $order->status;
        $newStatus = $validated['status'];

        $order->update([
            'status' => $newStatus,
        ]);

        // Record activity only when the status actually changes
        if ($oldStatus !== $newStatus) {
            ActivityLogger::log(
                'Order Status Updated',
                "Order {$order->order_number} status changed from {$oldStatus} to {$newStatus}",
                $order
            );
        }

        return redirect()
            ->route('admin.orders.show', $order)
            ->with('success', 'Order status updated successfully.');
    }
}
