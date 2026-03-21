<!DOCTYPE html>
<html>
<head>
    {{-- CRITICAL: This meta tag tells DomPDF to use UTF-8 --}}
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <style>
        body { 
            /* DejaVu Sans is the only built-in font that reliably renders the ₱ symbol */
            font-family: 'DejaVu Sans', sans-serif; 
            font-size: 13px;
            color: #333;
        }
        .header { text-align: center; color: #ff99aa; margin-bottom: 30px; }
        .section { margin-bottom: 25px; line-height: 1.5; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th { background-color: #f9f9f9; padding: 10px; border-bottom: 2px solid #ff99aa; text-align: left; }
        td { padding: 10px; border-bottom: 1px solid #eee; text-align: left; }
        .total-box { text-align: right; margin-top: 30px; color: #333; }
    </style>
</head>
<body>
    <div class="header">
        <h1>Glow Receipt</h1>
        <p style="color: #999;">Order Reference: {{ $order->order_number }}</p>
    </div>

    <div class="section">
        <strong style="color: #ff99aa;">Shipping To:</strong><br>
        {{ $order->user->name ?? 'Customer' }}<br>
        {{ $order->address }}, {{ $order->city }}<br>
        Phone: {{ $order->phone }}
    </div>

    <table>
        <thead>
            <tr>
                <th>Product</th>
                <th style="text-align: center;">Qty</th>
                <th style="text-align: right;">Price</th>
            </tr>
        </thead>
        <tbody>
            @foreach($order->orderItems as $item)
                <tr>
                    <td>
                        <strong>{{ $item->shade->product->name }}</strong><br>
                        <small style="color: #666;">Shade: {{ $item->shade->shade_name }}</small>
                    </td>
                    <td style="text-align: center;">{{ $item->quantity }}</td>
                    <td style="text-align: right;">₱{{ number_format($item->price, 2) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="total-box">
        <h3>Grand Total: <span style="color: #ff99aa;">₱{{ number_format($order->total_amount, 2) }}</span></h3>
    </div>

    <div style="margin-top: 50px; text-align: center; color: #999; font-size: 10px;">
        <p>Thank you for choosing GLOW. This is a computer-generated receipt.</p>
    </div>
</body>
</html>