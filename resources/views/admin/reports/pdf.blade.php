
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Luxury Fashion Hub - Sales Report</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            color: #222;
            margin: 30px;
        }
        .header {
            text-align: center;
            border-bottom: 2px solid #c49a54;
            padding-bottom: 15px;
        }
        .header h1 {
            margin-bottom: 5px;
        }
        .header p {
            color: #666;
        }
        .summary {
            display: flex;
            gap: 15px;
            margin: 25px 0;
        }
        .summary-card {
            flex: 1;
            border: 1px solid #ddd;
            padding: 15px;
        }
        .summary-card span {
            color: #666;
            font-size: 12px;
        }
        .summary-card h2 {
            margin-bottom: 0;
            font-size: 20px;
        }
        h3 {
            margin-top: 30px;
            color: #8b672f;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 12px;
            font-size: 12px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 9px;
            text-align: left;
        }
        th {
            background: #f2eee6;
        }
        .print-button {
            background: #c49a54;
            border: 0;
            padding: 11px 20px;
            cursor: pointer;
            font-weight: bold;
            margin-bottom: 20px;
        }
        .footer {
            margin-top: 30px;
            color: #777;
            font-size: 11px;
            text-align: center;
        }
        @media print {
            body { margin: 10px; }
            .print-button { display: none; }
        }
        @media (max-width: 650px) {
            .summary { flex-direction: column; }
        }
    </style>
</head>

<body>

    <button class="print-button" onclick="window.print()">
        Print / Save as PDF
    </button>

    <div class="header">
        <h1>Luxury Fashion Hub</h1>
        <h2>Sales and Order Report</h2>
        <p>
            Report Period:
            {{ \Carbon\Carbon::parse($from)->format('d M Y') }}
            to
            {{ \Carbon\Carbon::parse($to)->format('d M Y') }}
        </p>
        <p>Generated: {{ now()->format('d M Y, h:i A') }}</p>
    </div>

    <div class="summary">

        <div class="summary-card">
            <span>Total Orders</span>
            <h2>{{ number_format($totalOrders) }}</h2>
        </div>

        <div class="summary-card">
            <span>Total Order Value</span>
            <h2>₹{{ number_format((float)$totalOrderValue, 2) }}</h2>
        </div>

        <div class="summary-card">
            <span>Delivered Sales</span>
            <h2>₹{{ number_format((float)$totalSales, 2) }}</h2>
        </div>

    </div>

    <h3>Best-Selling Products</h3>

    <table>
        <thead>
            <tr>
                <th>Product</th>
                <th>Quantity Sold</th>
                <th>Revenue</th>
            </tr>
        </thead>
        <tbody>
            @forelse($bestSellingProducts as $product)
                <tr>
                    <td>{{ $product->product_name }}</td>
                    <td>{{ $product->total_sold }}</td>
                    <td>₹{{ number_format((float)$product->total_revenue, 2) }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="3">No product sales found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <h3>Order Details</h3>

    <table>
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
                    <td>{{ ucfirst($order->status) }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="5">No orders found for this period.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        Luxury Fashion Hub — Confidential Business Report
    </div>

</body>
</html>
