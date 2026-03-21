<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            line-height: 1.6;
            color: #333333;
            margin: 0;
            padding: 0;
        }
        .container {
            width: 100%;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            text-align: center;
            padding: 20px 0;
        }
        .header h1 {
            color: #ff99aa; /* Your Glow Pink */
            margin: 0;
        }
        .content {
            background-color: #ffffff;
            padding: 30px;
            border-radius: 8px;
            border: 1px solid #eeeeee;
        }
        .footer {
            text-align: center;
            font-size: 12px;
            color: #999999;
            padding: 20px;
        }
        .btn {
            display: inline-block;
            padding: 12px 25px;
            background-color: #ff99aa;
            color: #ffffff !important;
            text-decoration: none;
            border-radius: 50px;
            font-weight: bold;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>GLOW</h1>
        </div>
        
        <div class="content">
            <h2>Thank you for your order!</h2>
            <p>Hi {{ $order->user->name ?? 'Gorgeous' }},</p>
            
            <p>We’ve successfully received your order <strong>#{{ $order->order_number }}</strong> and our team is already getting it ready for you.</p>
            
            <p><strong>Order Summary:</strong></p>
            <ul>
                @foreach($order->orderItems as $item)
                    <li>{{ $item->shade->product->name }} ({{ $item->shade->shade_name }}) x {{ $item->quantity }}</li>
                @endforeach
            </ul>

            <p>We have attached your <strong>official PDF receipt</strong> to this email for your records.</p>
            
            <p>You can also track your order status by clicking the button below:</p>
            
            <div style="text-align: center;">
                <a href="{{ route('orders.my') }}" class="btn">View My Orders</a>
            </div>
        </div>

        <div class="footer">
            <p>&copy; {{ date('Y') }} Glow Cosmetics Shop. All rights reserved.</p>
            <p>If you have any questions, reply to this email or contact our support team.</p>
        </div>
    </div>
</body>
</html>