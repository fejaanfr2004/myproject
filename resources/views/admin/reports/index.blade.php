
@extends('layouts.admin')

@section('admin-content')

<style>
.reports-page {
    padding: 25px;
    color: #eee;
}
.reports-heading {
    margin-bottom: 25px;
}
.reports-heading h1 {
    font-size: 28px;
    margin-bottom: 8px;
}
.reports-heading p {
    color: #999;
}
.reports-section-title {
    color: #c49a54;
    font-size: 18px;
    margin: 28px 0 16px;
}
.reports-grid {
    display: grid;
    grid-template-columns: repeat(4, minmax(0, 1fr));
    gap: 18px;
}
.reports-card {
    background: #171717;
    border: 1px solid #333;
    border-radius: 10px;
    padding: 21px;
    min-width: 0;
}
.reports-card p {
    color: #aaa;
    font-size: 13px;
    margin-bottom: 12px;
}
.reports-card h2 {
    color: #c49a54;
    font-size: 25px;
    overflow-wrap: anywhere;
}
.reports-card small {
    color: #888;
    display: block;
    margin-top: 8px;
}
.reports-filter {
    background: #171717;
    border: 1px solid #333;
    border-radius: 10px;
    padding: 20px;
    display: flex;
    gap: 15px;
    flex-wrap: wrap;
    align-items: end;
}
.reports-field {
    display: flex;
    flex-direction: column;
    gap: 7px;
}
.reports-field label {
    color: #ccc;
    font-size: 13px;
}
.reports-field input {
    background: #252525;
    border: 1px solid #444;
    border-radius: 6px;
    color: white;
    padding: 11px;
    color-scheme: dark;
}
.reports-button {
    display: inline-block;
    background: #c49a54;
    color: #111;
    border: none;
    border-radius: 6px;
    padding: 11px 17px;
    text-decoration: none;
    font-weight: bold;
    cursor: pointer;
}
.reports-button:hover {
    background: #d8b16b;
}
.reports-button.secondary {
    background: #333;
    color: white;
    border: 1px solid #555;
}
.reports-button.secondary:hover {
    background: #444;
}
.reports-table-wrap {
    background: #171717;
    border: 1px solid #333;
    border-radius: 10px;
    overflow-x: auto;
}
.reports-table {
    width: 100%;
    min-width: 650px;
    border-collapse: collapse;
}
.reports-table th {
    color: #c49a54;
    text-align: left;
    padding: 15px;
    border-bottom: 1px solid #333;
}
.reports-table td {
    padding: 15px;
    border-bottom: 1px solid #303030;
    font-size: 14px;
}
.reports-table tr:last-child td {
    border-bottom: none;
}
.reports-status {
    display: inline-block;
    padding: 5px 9px;
    border-radius: 20px;
    background: #333;
    font-size: 12px;
    text-transform: capitalize;
}
.reports-status.pending { color: #f0c36d; }
.reports-status.processing { color: #80bfff; }
.reports-status.shipped { color: #c5a3ff; }
.reports-status.delivered { color: #80d69b; }
.reports-status.cancelled { color: #ff8585; }
.reports-pagination {
    padding: 15px;
}
.reports-pagination nav {
    display: flex;
    justify-content: space-between;
    gap: 10px;
    flex-wrap: wrap;
}
.reports-pagination a,
.reports-pagination span {
    color: #c49a54;
}
.reports-error {
    color: #ff8585;
    font-size: 13px;
}
@media(max-width:1000px) {
    .reports-grid {
        grid-template-columns: repeat(2, minmax(0, 1fr));
    }
}
@media(max-width:550px) {
    .reports-page { padding: 15px; }
    .reports-grid { grid-template-columns: 1fr; }
    .reports-heading h1 { font-size: 23px; }
    .reports-filter { align-items: stretch; }
    .reports-field { width: 100%; }
    .reports-field input { width: 100%; }
}
</style>

<div class="reports-page">

    <div class="reports-heading">
        <h1>Store Reports</h1>
        <p>Analyze your sales, orders and best-selling products.</p>
    </div>

    {{-- DATE FILTER --}}
    <h2 class="reports-section-title">Filter Reports</h2>

    <form method="GET"
          action="{{ route('admin.reports.index') }}"
          class="reports-filter">

        <div class="reports-field">
            <label for="from">Start Date</label>
            <input
                type="date"
                id="from"
                name="from"
                value="{{ $from }}"
                required
            >
        </div>

        <div class="reports-field">
            <label for="to">End Date</label>
            <input
                type="date"
                id="to"
                name="to"
                value="{{ $to }}"
                min="{{ $from }}"
                required
            >
        </div>

        <button type="submit" class="reports-button">
            Apply Filter
        </button>

        <a href="{{ route('admin.reports.index') }}"
           class="reports-button secondary">
            Reset
        </a>

    </form>
         <div style="display:flex;gap:12px;flex-wrap:wrap;margin-top:15px;">

        <a
            href="{{ route('admin.reports.export.excel', ['from' => $from, 'to' => $to]) }}"
            class="reports-button"
        >
            Download Excel CSV
        </a>

        <a
            href="{{ route('admin.reports.export.pdf', ['from' => $from, 'to' => $to]) }}"
            class="reports-button secondary"
            target="_blank"
        >
            View / Save PDF
        </a>

    </div>
    @if($errors->any())
        <p class="reports-error">
            {{ $errors->first() }}
        </p>
    @endif

    {{-- SALES STATISTICS --}}
    <h2 class="reports-section-title">Sales Overview</h2>

    <div class="reports-grid">

        <div class="reports-card">
            <p>Total Orders</p>
            <h2>{{ number_format($totalOrders) }}</h2>
            <small>Orders in selected period</small>
        </div>

        <div class="reports-card">
            <p>Total Order Value</p>
            <h2>₹{{ number_format((float)$totalOrderValue, 2) }}</h2>
            <small>Excluding cancelled orders</small>
        </div>

        <div class="reports-card">
            <p>Delivered Sales</p>
            <h2>₹{{ number_format((float)$totalSales, 2) }}</h2>
            <small>Delivered orders only</small>
        </div>

        <div class="reports-card">
            <p>Delivered Orders</p>
            <h2>{{ number_format($deliveredOrders) }}</h2>
            <small>Successfully delivered</small>
        </div>

    </div>

    {{-- ORDER STATISTICS --}}
    <h2 class="reports-section-title">Order Status Report</h2>

    <div class="reports-grid">

        <div class="reports-card">
            <p>Pending</p>
            <h2>{{ number_format($pendingOrders) }}</h2>
        </div>

        <div class="reports-card">
            <p>Processing</p>
            <h2>{{ number_format($processingOrders) }}</h2>
        </div>

        <div class="reports-card">
            <p>Shipped</p>
            <h2>{{ number_format($shippedOrders) }}</h2>
        </div>

        <div class="reports-card">
            <p>Cancelled</p>
            <h2>{{ number_format($cancelledOrders) }}</h2>
        </div>

    </div>

    {{-- BEST SELLING PRODUCTS --}}
    <h2 class="reports-section-title">Best-Selling Products</h2>

    <div class="reports-table-wrap">
        <table class="reports-table">
            <thead>
                <tr>
                    <th>Product Name</th>
                    <th>Quantity Sold</th>
                    <th>Total Revenue</th>
                </tr>
            </thead>

            <tbody>
                @forelse($bestSellingProducts as $product)
                    <tr>
                        <td>{{ $product->product_name }}</td>
                        <td>{{ number_format($product->total_sold) }}</td>
                        <td>
                            ₹{{ number_format((float)$product->total_revenue, 2) }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" style="text-align:center;color:#999;">
                            No product sales found for this period.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- ORDER DETAILS --}}
    <h2 class="reports-section-title">Order Details</h2>

    <div class="reports-table-wrap">
        <table class="reports-table">
            <thead>
                <tr>
                    <th>Order Number</th>
                    <th>Customer</th>
                    <th>Date</th>
                    <th>Total</th>
                    <th>Status</th>
                </tr>
            </thead>

            <tbody>
                @forelse($orders as $order)
                    <tr>
                        <td>{{ $order->order_number }}</td>
                        <td>{{ $order->customer_name }}</td>
                        <td>{{ $order->created_at->format('d M Y') }}</td>
                        <td>₹{{ number_format((float)$order->total, 2) }}</td>
                        <td>
                            <span class="reports-status {{ $order->status }}">
                                {{ $order->status }}
                            </span>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" style="text-align:center;color:#999;">
                            No orders found for the selected dates.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="reports-pagination">
        {{ $orders->links('pagination::bootstrap-5') }}
    </div>

</div>

@endsection
