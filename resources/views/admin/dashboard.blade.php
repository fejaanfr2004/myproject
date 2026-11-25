
@extends('layouts.admin')

@section('admin-content')

<style>
.dashboard-page {
    padding: 25px;
    color: #eee;
}
.dashboard-heading {
    margin-bottom: 25px;
}
.dashboard-heading h1 {
    font-size: 28px;
    margin-bottom: 8px;
}
.dashboard-heading p {
    color: #999;
}
.dashboard-section-title {
    color: #c49a54;
    font-size: 18px;
    margin: 28px 0 16px;
}
.dashboard-grid {
    display: grid;
    grid-template-columns: repeat(4, minmax(0, 1fr));
    gap: 18px;
}
.dashboard-card {
    background: #171717;
    border: 1px solid #333;
    border-radius: 10px;
    padding: 21px;
    min-width: 0;
}
.dashboard-card p {
    color: #aaa;
    font-size: 13px;
    margin-bottom: 12px;
}
.dashboard-card h2 {
    color: #c49a54;
    font-size: 27px;
    overflow-wrap: anywhere;
}
.dashboard-card small {
    color: #888;
    display: block;
    margin-top: 8px;
}
.dashboard-table-wrap {
    background: #171717;
    border: 1px solid #333;
    border-radius: 10px;
    overflow-x: auto;
}
.dashboard-table {
    width: 100%;
    border-collapse: collapse;
    min-width: 650px;
}
.dashboard-table th {
    color: #c49a54;
    text-align: left;
    padding: 15px;
    border-bottom: 1px solid #333;
}
.dashboard-table td {
    padding: 15px;
    border-bottom: 1px solid #303030;
    font-size: 14px;
}
.dashboard-table tr:last-child td {
    border-bottom: none;
}
.dashboard-link {
    color: #c49a54;
    text-decoration: none;
    font-weight: bold;
}
.dashboard-status {
    display: inline-block;
    padding: 5px 9px;
    border-radius: 20px;
    background: #333;
    font-size: 12px;
    text-transform: capitalize;
}
.dashboard-status.pending { color: #f0c36d; }
.dashboard-status.processing { color: #80bfff; }
.dashboard-status.shipped { color: #c5a3ff; }
.dashboard-status.delivered { color: #80d69b; }
.dashboard-status.cancelled { color: #ff8585; }

/* Monthly sales chart */
.sales-chart {
    background: #171717;
    border: 1px solid #333;
    border-radius: 10px;
    padding: 22px;
}
.sales-chart-subtitle {
    color: #999;
    font-size: 13px;
    margin-bottom: 25px;
}
.sales-chart-bars {
    height: 230px;
    display: flex;
    align-items: flex-end;
    justify-content: space-around;
    gap: 12px;
    border-bottom: 1px solid #444;
    padding: 0 5px;
}
.sales-chart-column {
    flex: 1;
    min-width: 0;
    height: 100%;
    display: flex;
    flex-direction: column;
    justify-content: flex-end;
    align-items: center;
    gap: 9px;
}
.sales-chart-value {
    color: #ccc;
    font-size: 11px;
    text-align: center;
    overflow-wrap: anywhere;
}
.sales-chart-bar-area {
    height: 175px;
    width: 100%;
    display: flex;
    align-items: flex-end;
    justify-content: center;
}
.sales-chart-bar {
    width: min(55px, 75%);
    min-height: 3px;
    background: linear-gradient(to top, #80602f, #d8b16b);
    border-radius: 6px 6px 0 0;
    transition: opacity .2s;
}
.sales-chart-bar:hover {
    opacity: .75;
}
.sales-chart-label {
    color: #aaa;
    font-size: 12px;
    text-align: center;
    white-space: nowrap;
}
.empty-chart {
    color: #999;
    text-align: center;
    padding: 30px 10px;
}
@media(max-width:1000px) {
    .dashboard-grid {
        grid-template-columns: repeat(2, minmax(0, 1fr));
    }
}
@media(max-width:550px) {
    .dashboard-page { padding: 15px; }
    .dashboard-grid {
        grid-template-columns: 1fr;
    }
    .dashboard-heading h1 { font-size: 23px; }
    .sales-chart { padding: 15px; }
    .sales-chart-bars { gap: 5px; }
    .sales-chart-value { font-size: 9px; }
    .sales-chart-label { font-size: 10px; }
}
</style>

<div class="dashboard-page">

    <div class="dashboard-heading">
        <h1>Welcome to Luxury Fashion Hub</h1>
        <p>Monitor your store, customers, products and orders.</p>
    </div>

    <h2 class="dashboard-section-title">Store Overview</h2>

    <div class="dashboard-grid">

        <div class="dashboard-card">
            <p>Total Products</p>
            <h2>{{ number_format($totalProducts) }}</h2>
        </div>

        <div class="dashboard-card">
            <p>Total Orders</p>
            <h2>{{ number_format($totalOrders) }}</h2>
            <small>{{ $todayOrders }} orders today</small>
        </div>

        <div class="dashboard-card">
            <p>Total Order Value</p>
            <h2>₹{{ number_format((float) $totalOrderValue, 2) }}</h2>
            <small>Excluding cancelled orders</small>
        </div>

        <div class="dashboard-card">
            <p>Total Sales</p>
            <h2>₹{{ number_format((float) $deliveredSales, 2) }}</h2>
            <small>Delivered orders only</small>
        </div>

    </div>

    <h2 class="dashboard-section-title">Order Statistics</h2>

    <div class="dashboard-grid">

        <div class="dashboard-card">
            <p>Pending Orders</p>
            <h2>{{ number_format($pendingOrders) }}</h2>
        </div>

        <div class="dashboard-card">
            <p>Processing Orders</p>
            <h2>{{ number_format($processingOrders) }}</h2>
        </div>

        <div class="dashboard-card">
            <p>Shipped Orders</p>
            <h2>{{ number_format($shippedOrders) }}</h2>
        </div>

        <div class="dashboard-card">
            <p>Delivered Orders</p>
            <h2>{{ number_format($deliveredOrders) }}</h2>
        </div>

        <div class="dashboard-card">
            <p>Cancelled Orders</p>
            <h2>{{ number_format($cancelledOrders) }}</h2>
        </div>

    </div>

    <h2 class="dashboard-section-title">User Statistics</h2>

    <div class="dashboard-grid">

        <div class="dashboard-card">
            <p>Total Users</p>
            <h2>{{ number_format($totalUsers) }}</h2>
        </div>

        <div class="dashboard-card">
            <p>Super Admins</p>
            <h2>{{ number_format($totalAdmins) }}</h2>
        </div>

        <div class="dashboard-card">
            <p>Customers</p>
            <h2>{{ number_format($totalCustomers) }}</h2>
        </div>

        <div class="dashboard-card">
            <p>Total Roles</p>
            <h2>{{ number_format($totalRoles) }}</h2>
        </div>

    </div>

    <h2 class="dashboard-section-title">Monthly Sales</h2>

    <div class="sales-chart">
        <p class="sales-chart-subtitle">
            Revenue from delivered orders over the last six months
        </p>

        @php
            $maxMonthlySales = max(
                collect($monthlySales)->pluck('sales')->toArray() ?: [0]
            );
        @endphp

        @if($maxMonthlySales > 0)
            <div class="sales-chart-bars">
                @foreach($monthlySales as $month)
                    @php
                        $barHeight = ($month['sales'] / $maxMonthlySales) * 100;
                    @endphp

                    <div class="sales-chart-column">
                        <div class="sales-chart-value">
                            ₹{{ number_format($month['sales'], 0) }}
                        </div>

                        <div class="sales-chart-bar-area">
                            <div
                                class="sales-chart-bar"
                                style="height: {{ $barHeight }}%;"
                                title="₹{{ number_format($month['sales'], 2) }}"
                            ></div>
                        </div>

                        <div class="sales-chart-label">
                            {{ $month['month'] }}
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="empty-chart">
                No delivered sales recorded in the last six months.
            </div>
        @endif
    </div>

    <h2 class="dashboard-section-title">Best-Selling Products</h2>

    <div class="dashboard-table-wrap">
        <table class="dashboard-table">
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
                            ₹{{ number_format((float) $product->total_revenue, 2) }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" style="text-align:center;color:#999;">
                            No sales data available yet.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <h2 class="dashboard-section-title">Recent Orders</h2>

    <div class="dashboard-table-wrap">
        <table class="dashboard-table">
            <thead>
                <tr>
                    <th>Order Number</th>
                    <th>Customer</th>
                    <th>Date</th>
                    <th>Total</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>

            <tbody>
                @forelse($recentOrders as $order)
                    <tr>
                        <td>{{ $order->order_number }}</td>
                        <td>{{ $order->customer_name }}</td>
                        <td>{{ $order->created_at->format('d M Y') }}</td>
                        <td>₹{{ number_format((float) $order->total, 2) }}</td>
                        <td>
                            <span class="dashboard-status {{ $order->status }}">
                                {{ $order->status }}
                            </span>
                        </td>
                        <td>
                            <a
                                href="{{ route('admin.orders.show', $order) }}"
                                class="dashboard-link"
                                data-admin-link
                            >
                                View
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" style="text-align:center;color:#999;">
                            No orders placed yet.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <p style="margin-top:18px;">
        <a href="{{ route('admin.orders.index') }}"
           class="dashboard-link"
           data-admin-link>
            View All Orders →
        </a>
    </p>

</div>

@endsection
