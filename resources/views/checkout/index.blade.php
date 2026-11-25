
@extends('layouts.app')

@section('title', 'Checkout | Luxury Fashion Hub')

@push('styles')
<style>
.checkout-container {
    max-width: 1100px;
    margin: 50px auto;
    padding: 20px;
}

.checkout-heading {
    text-align: center;
    font-size: 30px;
    letter-spacing: 2px;
    margin-bottom: 35px;
}

.checkout-grid {
    display: grid;
    grid-template-columns: 1.4fr 1fr;
    gap: 25px;
}

.checkout-box {
    background: white;
    padding: 25px;
    border: 1px solid #eee;
    margin-bottom: 20px;
}

.checkout-box h2 {
    font-size: 20px;
    margin-bottom: 22px;
    color: #9b7945;
}

.checkout-field {
    margin-bottom: 17px;
}

.checkout-field label {
    display: block;
    margin-bottom: 7px;
    font-weight: bold;
    font-size: 14px;
}

.checkout-field input,
.checkout-field textarea {
    width: 100%;
    padding: 12px;
    border: 1px solid #ccc;
    font-size: 14px;
    font-family: inherit;
}

.checkout-field textarea {
    min-height: 100px;
    resize: vertical;
}

.checkout-row {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 15px;
}

.checkout-summary-item {
    display: flex;
    justify-content: space-between;
    gap: 12px;
    padding: 12px 0;
    border-bottom: 1px solid #eee;
    font-size: 14px;
}

.checkout-total {
    display: flex;
    justify-content: space-between;
    margin-top: 20px;
    font-size: 21px;
    font-weight: bold;
    color: #9b7945;
}

.payment-option {
    padding: 15px;
    border: 1px solid #c9a96e;
    background: #faf8f3;
    margin-top: 15px;
}

.checkout-submit {
    width: 100%;
    padding: 16px;
    background: #111;
    color: white;
    border: none;
    font-size: 15px;
    font-weight: bold;
    letter-spacing: 1px;
    cursor: pointer;
    margin-top: 20px;
}

.checkout-submit:hover {
    background: #c9a96e;
    color: #111;
}

.checkout-alert {
    background: #fff0f0;
    color: #a83232;
    padding: 15px;
    margin-bottom: 20px;
    border: 1px solid #e8baba;
}

.checkout-back {
    display: inline-block;
    margin-top: 18px;
    color: #9b7945;
    text-decoration: none;
    font-weight: bold;
}

@media(max-width: 750px) {
    .checkout-grid {
        grid-template-columns: 1fr;
    }

    .checkout-heading {
        font-size: 23px;
    }
}

@media(max-width: 450px) {
    .checkout-row {
        grid-template-columns: 1fr;
        gap: 0;
    }
}
</style>
@endpush

@section('content')

<div class="checkout-container">

    <h1 class="checkout-heading">SECURE CHECKOUT</h1>

    @if($errors->any())
        <div class="checkout-alert">
            <strong>Please correct the following:</strong>

            <ul style="margin:10px 0 0 20px;">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="checkout-grid">

        {{-- CUSTOMER INFORMATION --}}

        <div>

            <form action="{{ route('checkout.store') }}" method="POST">
                @csrf

                <div class="checkout-box">

                    <h2>Delivery Information</h2>

                    <div class="checkout-field">
                        <label>Full Name *</label>
                        <input
                            type="text"
                            name="customer_name"
                            value="{{ old('customer_name', auth()->user()?->name) }}"
                            required
                        >
                    </div>

                    <div class="checkout-field">
                        <label>Email Address *</label>
                        <input
                            type="email"
                            name="customer_email"
                            value="{{ old('customer_email', auth()->user()?->email) }}"
                            required
                        >
                    </div>

                    <div class="checkout-field">
                        <label>Phone Number *</label>
                        <input
                            type="tel"
                            name="customer_phone"
                            value="{{ old('customer_phone') }}"
                            maxlength="20"
                            required
                        >
                    </div>

                    <div class="checkout-field">
                        <label>Complete Delivery Address *</label>
                        <textarea
                            name="shipping_address"
                            required
                        >{{ old('shipping_address') }}</textarea>
                    </div>

                    <div class="checkout-row">

                        <div class="checkout-field">
                            <label>City *</label>
                            <input
                                type="text"
                                name="city"
                                value="{{ old('city') }}"
                                required
                            >
                        </div>

                        <div class="checkout-field">
                            <label>State *</label>
                            <input
                                type="text"
                                name="state"
                                value="{{ old('state') }}"
                                required
                            >
                        </div>

                    </div>

                    <div class="checkout-field">
                        <label>PIN Code *</label>
                        <input
                            type="text"
                            name="postal_code"
                            value="{{ old('postal_code') }}"
                            maxlength="20"
                            required
                        >
                    </div>

                    <div class="checkout-field">
                        <label>Order Notes (Optional)</label>
                        <textarea
                            name="notes"
                            placeholder="Any special delivery instructions?"
                        >{{ old('notes') }}</textarea>
                    </div>

                </div>

                {{-- PAYMENT --}}

                <div class="checkout-box">

                    <h2>Payment Method</h2>

                    <div class="payment-option">
                        <strong>Cash on Delivery (COD)</strong>
                        <p style="margin-top:7px;color:#666;font-size:13px;">
                            Pay when your order arrives at your doorstep.
                        </p>
                    </div>

                    <button type="submit" class="checkout-submit">
                        PLACE ORDER
                    </button>

                    <a href="{{ route('cart.index') }}" class="checkout-back">
                        ← Back to Shopping Bag
                    </a>

                </div>

            </form>

        </div>

        {{-- ORDER SUMMARY --}}

        <div>

            <div class="checkout-box">

                <h2>Your Order Summary</h2>

                @foreach($cart as $item)

                    <div class="checkout-summary-item">

                        <div>
                            <strong>{{ $item['name'] }}</strong>
                            <p style="color:#777;margin-top:5px;">
                                Quantity: {{ $item['quantity'] }}
                            </p>
                        </div>

                        <strong>
                            ₹{{ number_format($item['price'] * $item['quantity'], 2) }}
                        </strong>

                    </div>

                @endforeach

                <div class="checkout-summary-item">
                    <span>Subtotal</span>
                    <strong>₹{{ number_format($total, 2) }}</strong>
                </div>

                <div class="checkout-summary-item">
                    <span>Shipping</span>
                    <strong>FREE</strong>
                </div>

                <div class="checkout-total">
                    <span>Total</span>
                    <span>₹{{ number_format($total, 2) }}</span>
                </div>

                <p style="color:#777;font-size:12px;margin-top:15px;">
                    Final product prices and stock availability are checked
                    again when you place your order.
                </p>

            </div>

        </div>

    </div>

</div>

@endsection
