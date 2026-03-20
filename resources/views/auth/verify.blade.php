<!DOCTYPE html>
<html>
<head>
    <style>
        .button {
            background-color: #ec4899;
            color: white !important;
            padding: 12px 25px;
            text-decoration: none;
            border-radius: 50px;
            font-weight: bold;
            display: inline-block;
        }
        .header { background: #212529; padding: 20px; text-align: center; }
        .body { padding: 40px; font-family: sans-serif; color: #333; line-height: 1.6; }
    </style>
</head>
<body>
    <div class="header">
        <h1 style="color: white; margin: 0;">GLOW</h1>
    </div>
    <div class="body text-center">
        <h2>Welcome to the Shop!</h2>
        <p>Please click the button below to verify your email address and start your makeup journey with us.</p>
        <a href="{{ $url }}" class="button">Verify Email Address</a>
        <p style="margin-top: 30px; font-size: 0.8rem; color: #999;">If you did not create an account, no further action is required.</p>
    </div>
</body>
</html>