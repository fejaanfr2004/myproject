<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;

class ProductController extends Controller
{
    // Show all active products
    public function index(Request $request)
    {
        $query = Product::where('is_active', true);

        if ($request->filled('search')) {
            $search = $request->search;

            $query->where('name', 'like', "%{$search}%");
        }

        $products = $query->latest()->get();

        $cart = session()->get('cart', []);
        $cartCount = array_sum(array_column($cart, 'quantity'));

        return view('products.index', compact('products', 'cartCount'));
    }

    // Show single product
    public function show($id)
    {
        $product = Product::where('is_active', true)
            ->findOrFail($id);

        $cart = session()->get('cart', []);
        $cartCount = array_sum(array_column($cart, 'quantity'));

        return view('products.show', compact('product', 'cartCount'));
    }

    // Add product to cart
    public function addToCart(Request $request)
    {
        $request->validate([
            'product_id' => 'required|integer|exists:products,id',
            'quantity' => 'nullable|integer|min:1|max:99',
        ]);

        $product = Product::where('is_active', true)
            ->findOrFail($request->product_id);

        $quantity = (int) ($request->quantity ?? 1);

        if ($product->stock < $quantity) {
            return back()->with('error', 'Sorry, insufficient stock available.');
        }

        $cart = session()->get('cart', []);

        $productId = $product->id;

        $existingQuantity = $cart[$productId]['quantity'] ?? 0;
        $newQuantity = $existingQuantity + $quantity;

        if ($newQuantity > $product->stock) {
            return back()->with('error', 'You cannot add more than the available stock.');
        }

        $cart[$productId] = [
            'id' => $product->id,
            'name' => $product->name,
            'price' => $product->price,
            'image' => $product->image,
            'quantity' => $newQuantity,
        ];

        session()->put('cart', $cart);

        return redirect()
            ->route('cart.index')
            ->with('success', 'Product added to your shopping bag!');
    }

    // Display shopping cart
    public function cart()
    {
        $cart = session()->get('cart', []);

        $cartCount = array_sum(array_column($cart, 'quantity'));

        $total = 0;

        foreach ($cart as $item) {
            $total += $item['price'] * $item['quantity'];
        }

        return view('cart.index', compact(
            'cart',
            'cartCount',
            'total'
        ));
    }

    // Update cart quantity
    public function updateCart(Request $request)
    {
        $request->validate([
            'product_id' => 'required|integer',
            'quantity' => 'required|integer|min:1|max:99',
        ]);

        $cart = session()->get('cart', []);

        $productId = $request->product_id;

        if (!isset($cart[$productId])) {
            return back()->with('error', 'Product not found in your cart.');
        }

        $product = Product::where('is_active', true)
            ->find($productId);

        if (!$product) {
            unset($cart[$productId]);
            session()->put('cart', $cart);

            return back()->with('error', 'This product is no longer available.');
        }

        if ($request->quantity > $product->stock) {
            return back()->with('error', 'Only ' . $product->stock . ' items are available.');
        }

        $cart[$productId]['quantity'] = (int) $request->quantity;

        session()->put('cart', $cart);

        return back()->with('success', 'Cart updated successfully!');
    }

    // Remove product from cart
    public function removeFromCart(Request $request)
    {
        $request->validate([
            'product_id' => 'required|integer',
        ]);

        $cart = session()->get('cart', []);

        $productId = $request->product_id;

        if (isset($cart[$productId])) {
            unset($cart[$productId]);
        }

        session()->put('cart', $cart);

        return back()->with('success', 'Product removed from your cart.');
    }
}
