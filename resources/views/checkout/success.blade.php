
@extends('layouts.app')

@section('title', 'Order Successful | Luxury Fashion Hub')

@push('styles')
<style>
.success-container {
    max-width: 700px;
    margin: 80px auto;
    padding: 25px;
    text-align: center;
}

.success-icon {
    width: 85px;
    height: 85px;
    background: #e8f5e9;
    color: #258443;
    border-radius: 50%;
    display: grid;
    place-items: center;
    font-size: 45px;
    margin: 0 auto 25px;
}

.success-container h1 {
    font-size: 30px;
    margin-bottom: 15px;
}

.success-container p {
    color: #666;
    line-height: 1.7;
}

.success-order {
    background: white;
    border: 1px solid #eee;
    padding: 25px;
    margin: 30px 0;
    text-align: left;
}

.success-order div {
    display: flex;
    justify-content: space-between;
    gap: 15px;
    padding: 12px 0;
    border-bottom: 1px solid #eee;
}

.success-order strong {
    color: #9b7945;
}

.success-button {
    display: inline-block;
    padding: 15px 25px;
    background: #111;
    color: white;
    text-decoration: none;
    font-weight: bold;
}

.success-button:hover {
    background: #c9a96e;
    color: #111;
}

@media(max-width:500px) {
    .success-container h1 {
        font-size: 24px;
    }
}
</style>
@endpush

@section('content')

<div class="success-container">

    <div class="success-icon">✓</div>

    <h1>Thank You For Your Order!</h1>

    <p>
        Your order has been placed successfully.
        We appreciate your shopping with Luxury Fashion Hub.
    </p>

    <div class="success-order">

        <div>
            <span>Order Number</span>
            <strong>{{ $order->order_number }}</strong>
        </div>

        <div>
            <span>Customer</span>
            <strong>{{ $order->customer_name }}</strong>
        </div>

        <div>
            <span>Payment Method</span>
            <strong>Cash on Delivery</strong>
        </div>

        <div>
            <span>Order Status</span>
            <strong>{{ ucfirst($order->status) }}</strong>
        </div>

        <div>
            <span>Total Amount</span>
            <strong>₹{{ number_format($order->total, 2) }}</strong>
        </div>

    </div>

    <a href="{{ route('products.index') }}" class="success-button">
        CONTINUE SHOPPING
    </a>

</div>

@endsection
