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
            background-color: #f9f9f9;
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
            color: #ff99aa; /* Glow Pink */
            margin: 0;
            letter-spacing: 2px;
        }
        .content {
            background-color: #ffffff;
            padding: 30px;
            border-radius: 8px;
            border: 1px solid #eeeeee;
        }
        .status-box {
            text-align: center;
            margin: 25px 0;
            padding: 15px;
            background-color: #fff0f2;
            border-radius: 12px;
            border: 1px dashed #ff99aa;
        }
        .status-text {
            color: #ff99aa;
            font-size: 20px;
            font-weight: bold;
            text-transform: uppercase;
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
            <h2 style="margin-top: 0;">Order Status Update</h2>
            <p>Hi {{ $order->user->name ?? 'Gorgeous' }},</p>
            
            <p>Great news! The status of your order <strong>#{{ $order->order_number }}</strong> has been updated:</p>
            
            <div class="status-box">
                <span class="status-text">{{ $order->status }}</span>
            </div>

            <p>We’ve attached your <strong>PDF receipt</strong> to this email so you have the latest details for your records.</p>
            
            <p>Want to see the full details? You can view your order history by clicking the button below:</p>
            
            <div style="text-align: center;">
                <a href="{{ route('orders.my') }}" class="btn">View My Orders</a>
            </div>
        </div>

        <div class="footer">
            <p>&copy; {{ date('Y') }} Glow Cosmetics Shop. All rights reserved.</p>
            <p>If you have any questions about this update, feel free to contact our support team.</p>
        </div>
    </div>
</body>
</html>