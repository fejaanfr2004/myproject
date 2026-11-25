
@extends('layouts.admin')

@section('admin-content')

<style>
.orders-page {
    padding: 25px;
    color: #eee;
}
.orders-page h1 {
    font-size: 28px;
    margin-bottom: 8px;
}
.orders-subtitle {
    color: #999;
    margin-bottom: 25px;
}
.orders-toolbar {
    display: flex;
    gap: 12px;
    flex-wrap: wrap;
    margin-bottom: 22px;
}
.orders-toolbar input,
.orders-toolbar select {
    background: #171717;
    color: #fff;
    border: 1px solid #383838;
    padding: 12px 14px;
    border-radius: 7px;
}
.orders-toolbar input {
    flex: 1;
    min-width: 200px;
}
.orders-btn {
    background: #c49a54;
    color: #111;
    border: none;
    border-radius: 7px;
    padding: 12px 20px;
    font-weight: bold;
    cursor: pointer;
    text-decoration: none;
}
.orders-table-wrap {
    overflow-x: auto;
    background: #171717;
    border: 1px solid #333;
    border-radius: 10px;
}
.orders-table {
    width: 100%;
    border-collapse: collapse;
    min-width: 850px;
}
.orders-table th {
    color: #c49a54;
    text-align: left;
    padding: 16px;
    border-bottom: 1px solid #383838;
    font-size: 13px;
}
.orders-table td {
    padding: 16px;
    border-bottom: 1px solid #303030;
    font-size: 14px;
}
.orders-table tr:last-child td {
    border-bottom: none;
}
.order-number {
    color: #d5ad6a;
    font-weight: bold;
}
.order-status {
    display: inline-block;
    padding: 6px 10px;
    border-radius: 20px;
    font-size: 12px;
    text-transform: capitalize;
    background: #333;
}
.status-pending { color: #f0c36d; }
.status-processing { color: #80bfff; }
.status-shipped { color: #c5a3ff; }
.status-delivered { color: #80d69b; }
.status-cancelled { color: #ff8585; }
.order-view {
    color: #c49a54;
    text-decoration: none;
    font-weight: bold;
}
.orders-empty {
    padding: 35px;
    text-align: center;
    color: #aaa;
}
.orders-pagination {
    margin-top: 20px;
}
.orders-pagination nav {
    display: flex;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 12px;
}
.orders-pagination a,
.orders-pagination span {
    color: #c49a54;
}
@media(max-width:600px) {
    .orders-page { padding: 15px; }
    .orders-page h1 { font-size: 23px; }
}
</style>

<div class="orders-page">

    <h1>Order Management</h1>
    <p class="orders-subtitle">
        View and manage all customer orders.
    </p>

    <form method="GET"
          action="{{ route('admin.orders.index') }}"
          class="orders-toolbar">

        <input
            type="text"
            name="search"
            placeholder="Search order number, customer, email or phone..."
            value="{{ request('search') }}"
        >

        <select name="status">
            <option value="">All Statuses</option>
            @foreach(['pending', 'processing', 'shipped', 'delivered', 'cancelled'] as $status)
                <option value="{{ $status }}"
                    @selected(request('status') === $status)>
                    {{ ucfirst($status) }}
                </option>
            @endforeach
        </select>

        <button type="submit" class="orders-btn">
            Search
        </button>

        <a href="{{ route('admin.orders.index') }}"
           class="orders-btn">
            Reset
        </a>

    </form>

    <div class="orders-table-wrap">
        <table class="orders-table">
            <thead>
                <tr>
                    <th>Order Number</th>
                    <th>Customer</th>
                    <th>Phone</th>
                    <th>Date</th>
                    <th>Total</th>
                    <th>Payment</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>

            <tbody>
                @forelse($orders as $order)
                    <tr>
                        <td class="order-number">
                            {{ $order->order_number }}
                        </td>

                        <td>
                            {{ $order->customer_name }}
                            <br>
                            <small style="color:#999;">
                                {{ $order->customer_email }}
                            </small>
                        </td>

                        <td>{{ $order->customer_phone }}</td>

                        <td>
                            {{ $order->created_at->format('d M Y') }}
                        </td>

                        <td>
                            ₹{{ number_format((float) $order->total, 2) }}
                        </td>

                        <td>
                            {{ strtoupper($order->payment_method) }}
                        </td>

                        <td>
                            <span class="order-status status-{{ $order->status }}">
                                {{ ucfirst($order->status) }}
                            </span>
                        </td>

                        <td>
                            <a
                                href="{{ route('admin.orders.show', $order) }}"
                                class="order-view"
                                data-admin-link
                            >
                                View Order →
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="orders-empty">
                            No orders found.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="orders-pagination">
        {{ $orders->links() }}
    </div>

</div>

@endsection
