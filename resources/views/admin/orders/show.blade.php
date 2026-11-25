
@extends('layouts.admin')

@section('admin-content')

<style>
.order-details-page {
    padding: 25px;
    color: #eee;
}
.order-details-page h1 {
    font-size: 27px;
    margin-bottom: 8px;
}
.order-details-subtitle {
    color: #999;
    margin-bottom: 25px;
}
.order-back {
    display: inline-block;
    color: #c49a54;
    text-decoration: none;
    margin-bottom: 20px;
}
.order-message {
    background: #183523;
    color: #9be0ae;
    border: 1px solid #326443;
    padding: 13px;
    border-radius: 7px;
    margin-bottom: 20px;
}
.order-error {
    background: #401f1f;
    color: #ffaaaa;
    border: 1px solid #743737;
    padding: 13px;
    border-radius: 7px;
    margin-bottom: 20px;
}
.order-details-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 20px;
}
.order-card {
    background: #171717;
    border: 1px solid #333;
    border-radius: 10px;
    padding: 22px;
    margin-bottom: 20px;
}
.order-card h2 {
    color: #c49a54;
    font-size: 17px;
    margin-bottom: 18px;
}
.order-info {
    display: flex;
    justify-content: space-between;
    gap: 15px;
    padding: 11px 0;
    border-bottom: 1px solid #303030;
}
.order-info:last-child {
    border-bottom: none;
}
.order-info span:first-child {
    color: #999;
}
.order-info span:last-child {
    text-align: right;
    overflow-wrap: anywhere;
}
.order-items {
    width: 100%;
    border-collapse: collapse;
}
.order-items th {
    color: #c49a54;
    text-align: left;
    padding: 12px 8px;
    border-bottom: 1px solid #383838;
}
.order-items td {
    padding: 14px 8px;
    border-bottom: 1px solid #303030;
}
.order-product {
    display: flex;
    align-items: center;
    gap: 12px;
}
.order-product img {
    width: 55px;
    height: 65px;
    object-fit: cover;
    border-radius: 5px;
}
.order-product-name {
    font-weight: bold;
}
.order-status-form select {
    width: 100%;
    padding: 12px;
    background: #222;
    color: white;
    border: 1px solid #444;
    border-radius: 6px;
    margin: 10px 0 15px;
}
.order-update-btn {
    background: #c49a54;
    color: #111;
    padding: 12px 20px;
    border: none;
    border-radius: 6px;
    font-weight: bold;
    cursor: pointer;
}
.order-total {
    color: #c49a54;
    font-size: 21px;
    font-weight: bold;
}
.order-items-wrap {
    overflow-x: auto;
}
@media(max-width:750px) {
    .order-details-grid {
        grid-template-columns: 1fr;
    }
    .order-details-page {
        padding: 15px;
    }
}
</style>

<div class="order-details-page">

    <a href="{{ route('admin.orders.index') }}"
       class="order-back"
       data-admin-link>
        ← Back to Orders
    </a>

    <h1>Order Details</h1>

    <p class="order-details-subtitle">
        Order {{ $order->order_number }}
    </p>

    @if(session('success'))
        <div class="order-message">
            {{ session('success') }}
        </div>
    @endif

    @if($errors->any())
        <div class="order-error">
            @foreach($errors->all() as $error)
                <div>{{ $error }}</div>
            @endforeach
        </div>
    @endif

    <div class="order-details-grid">

        <div class="order-card">
            <h2>Customer Information</h2>

            <div class="order-info">
                <span>Name</span>
                <span>{{ $order->customer_name }}</span>
            </div>

            <div class="order-info">
                <span>Email</span>
                <span>{{ $order->customer_email }}</span>
            </div>

            <div class="order-info">
                <span>Phone</span>
                <span>{{ $order->customer_phone }}</span>
            </div>

            <div class="order-info">
                <span>Customer Account</span>
                <span>
                    {{ $order->user?->email ?? 'Guest Checkout' }}
                </span>
            </div>
        </div>

        <div class="order-card">
            <h2>Delivery Address</h2>

            <div class="order-info">
                <span>Address</span>
                <span>{{ $order->shipping_address }}</span>
            </div>

            <div class="order-info">
                <span>City</span>
                <span>{{ $order->city }}</span>
            </div>

            <div class="order-info">
                <span>State</span>
                <span>{{ $order->state }}</span>
            </div>

            <div class="order-info">
                <span>Postal Code</span>
                <span>{{ $order->postal_code }}</span>
            </div>
        </div>

    </div>

    <div class="order-card">
        <h2>Products Ordered</h2>

        <div class="order-items-wrap">
            <table class="order-items">
                <thead>
                    <tr>
                        <th>Product</th>
                        <th>Price</th>
                        <th>Quantity</th>
                        <th>Total</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach($order->items as $item)
                        <tr>
                            <td>
                                <div class="order-product">
                                    @if($item->product_image)
                                        <img
                                            src="{{ asset('storage/' . $item->product_image) }}"
                                            alt="{{ $item->product_name }}"
                                        >
                                    @endif

                                    <span class="order-product-name">
                                        {{ $item->product_name }}
                                    </span>
                                </div>
                            </td>

                            <td>
                                ₹{{ number_format((float) $item->unit_price, 2) }}
                            </td>

                            <td>{{ $item->quantity }}</td>

                            <td>
                                ₹{{ number_format((float) $item->line_total, 2) }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div style="margin-top:20px;">
            <div class="order-info">
                <span>Subtotal</span>
                <span>₹{{ number_format((float) $order->subtotal, 2) }}</span>
            </div>

            <div class="order-info">
                <span>Shipping</span>
                <span>₹{{ number_format((float) $order->shipping_cost, 2) }}</span>
            </div>

            <div class="order-info">
                <strong>Grand Total</strong>
                <span class="order-total">
                    ₹{{ number_format((float) $order->total, 2) }}
                </span>
            </div>
        </div>
    </div>

    <div class="order-details-grid">

        <div class="order-card">
            <h2>Payment Information</h2>

            <div class="order-info">
                <span>Payment Method</span>
                <span>{{ strtoupper($order->payment_method) }}</span>
            </div>

            <div class="order-info">
                <span>Payment Status</span>
                <span>{{ ucfirst($order->payment_status) }}</span>
            </div>

            <div class="order-info">
                <span>Order Date</span>
                <span>{{ $order->created_at->format('d M Y, h:i A') }}</span>
            </div>
        </div>

        <div class="order-card">
            <h2>Order Status</h2>

            <div class="order-info">
                <span>Current Status</span>
                <strong>{{ ucfirst($order->status) }}</strong>
            </div>

            @can('manage-orders')
                <form
                    action="{{ route('admin.orders.updateStatus', $order) }}"
                    method="POST"
                    class="order-status-form"
                >
                    @csrf
                    @method('PATCH')

                    <label for="status">Update Order Status</label>

                    <select name="status" id="status" required>
                        @foreach(['pending', 'processing', 'shipped', 'delivered', 'cancelled'] as $status)
                            <option value="{{ $status }}"
                                @selected($order->status === $status)>
                                {{ ucfirst($status) }}
                            </option>
                        @endforeach
                    </select>

                    <button type="submit" class="order-update-btn">
                        UPDATE STATUS
                    </button>
                </form>
            @endcan
        </div>

    </div>

    @if($order->notes)
        <div class="order-card">
            <h2>Customer Notes</h2>
            <p>{{ $order->notes }}</p>
        </div>
    @endif

</div>

@endsection
