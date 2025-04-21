<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Password Reset Request</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            color: #333;
            line-height: 1.6;
            padding: 20px;
        }

        .container {
            max-width: 600px;
            margin: auto;
            background: #ffffff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .btn {
            display: inline-block;
            background: #4A90E2;
            color: white;
            padding: 12px 20px;
            border-radius: 5px;
            text-decoration: none;
            font-weight: bold;
            text-align: center;
            font-size: 16px;
        }

        .btn:hover {
            background-color: #357ABD;
        }

        .footer {
            margin-top: 30px;
            font-size: 12px;
            color: #888;
            text-align: center;
        }

        .footer a {
            color: #4A90E2;
            text-decoration: none;
        }
    </style>
</head>

<body>
    <div class="container">
        <h2>Hello, {{ $user->name ?? 'there' }} 👋</h2>

        <p>We received a request to reset your password. If this was you, just click the button below to set a new
            password:</p>

        <p style="text-align: center;">
            <a href="{{ url('/reset-password-view?token=' . $reset_token . '&email=' . urlencode($user->email)) }}"
                class="btn">Reset Password</a>
        </p>

        <p>If you didn’t request a password reset, you can safely ignore this email.</p>

        <p>Best regards,<br>
            The {{ config('app.name') }} Team</p>

        <div class="footer">
            <p>If you’re having trouble clicking the button, copy and paste the following link into your browser:</p>
            <p>
                <a href="{{ url('/reset-password-view?token=' . $reset_token . '&email=' . urlencode($user->email)) }}">
                    {{ url('/reset-password-view?token=' . $reset_token . '&email=' . urlencode($user->email)) }}
                </a>
            </p>
        </div>
    </div>
</body>

</html>
