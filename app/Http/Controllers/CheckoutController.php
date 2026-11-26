<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use App\Support\AdminNotificationService;
class CheckoutController extends Controller
{
    // Display checkout page
    public function show()
    {
        $cart = session()->get('cart', []);

        if (empty($cart)) {
            return redirect()
                ->route('cart.index')
                ->with('error', 'Your shopping cart is empty.');
        }

        $cartCount = array_sum(array_column($cart, 'quantity'));
        $total = 0;

        foreach ($cart as $item) {
            $total += $item['price'] * $item['quantity'];
        }

        return view('checkout.index', compact(
            'cart',
            'cartCount',
            'total'
        ));
    }

    // Place customer order
    public function store(Request $request)
    {
        $validated = $request->validate([
            'customer_name' => ['required', 'string', 'max:255'],
            'customer_email' => ['required', 'email', 'max:255'],
            'customer_phone' => ['required', 'string', 'max:20'],
            'shipping_address' => ['required', 'string', 'max:2000'],
            'city' => ['required', 'string', 'max:100'],
            'state' => ['required', 'string', 'max:100'],
            'postal_code' => ['required', 'string', 'max:20'],
            'notes' => ['nullable', 'string', 'max:2000'],
        ]);

        $cart = session()->get('cart', []);

        if (empty($cart)) {
            return redirect()
                ->route('cart.index')
                ->with('error', 'Your shopping cart is empty.');
        }

        $order = DB::transaction(function () use ($validated, $cart) {

            $orderItems = [];
            $subtotal = 0;

            foreach ($cart as $productId => $cartItem) {

                $product = Product::where('is_active', true)
                    ->lockForUpdate()
                    ->find($productId);

                if (!$product) {
                    throw ValidationException::withMessages([
                        'cart' => 'A product in your cart is no longer available. Please update your cart.',
                    ]);
                }

                $quantity = (int) ($cartItem['quantity'] ?? 0);

                if ($quantity < 1 || $quantity > $product->stock) {
                    throw ValidationException::withMessages([
                        'cart' => 'Insufficient stock for ' . $product->name . '. Available stock: ' . $product->stock,
                    ]);
                }

                // Always use the current database price
                $unitPrice = (float) $product->price;
                $lineTotal = $unitPrice * $quantity;

                $subtotal += $lineTotal;

                $orderItems[] = [
                    'product_id' => $product->id,
                    'product_name' => $product->name,
                    'product_image' => $product->image,
                    'unit_price' => $unitPrice,
                    'quantity' => $quantity,
                    'line_total' => $lineTotal,
                    'product' => $product,
                ];
            }

            $orderNumber = 'LFH-' . strtoupper(Str::random(10));

            $order = Order::create([
                'user_id' => auth()->id(),
                'order_number' => $orderNumber,
                'customer_name' => $validated['customer_name'],
                'customer_email' => $validated['customer_email'],
                'customer_phone' => $validated['customer_phone'],
                'shipping_address' => $validated['shipping_address'],
                'city' => $validated['city'],
                'state' => $validated['state'],
                'postal_code' => $validated['postal_code'],
                'subtotal' => $subtotal,
                'shipping_cost' => 0,
                'total' => $subtotal,
                'payment_method' => 'cod',
                'payment_status' => 'pending',
                'status' => 'pending',
                'notes' => $validated['notes'] ?? null,
            ]);

            foreach ($orderItems as $item) {

                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item['product_id'],
                    'product_name' => $item['product_name'],
                    'product_image' => $item['product_image'],
                    'unit_price' => $item['unit_price'],
                    'quantity' => $item['quantity'],
                    'line_total' => $item['line_total'],
                ]);

               $item['product']->decrement('stock', $item['quantity']);

// Refresh product stock after purchase
$item['product']->refresh();

// Notify Super Admin when stock reaches 5 or fewer units
if ($item['product']->stock <= 5 && $item['product']->stock + $item['quantity'] > 5) {
    AdminNotificationService::send(
        'Low Stock Alert',
        'Product "' . $item['product']->name . '" has only ' . $item['product']->stock . ' units remaining after a customer purchase.',
        'low_stock',
        route('admin.products.index')
    );
}
            }

            return $order;
        });

        // Clear cart only after the order is successfully saved
        // Send notification to Super Admin after successful order placement
AdminNotificationService::send(
    'New Order Received',
    'A new order ' . $order->order_number .
    ' has been placed by ' . $order->customer_name .
    '. Total amount: ₹' . number_format($order->total, 2),
    'order',
    route('admin.orders.show', $order->id)
);

// Clear cart only after the order is successfully saved
session()->forget('cart');

        return view('checkout.success', compact('order'));
    }
}
