<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Email Verification</title>
</head>

<body>
    <h1>Hello, {{ $user->name }}!</h1>
    <p>Please click the link below to verify your email:</p>
    <a href="{{ url('/api/verify-email/' . $user->id . '?token=' . $user->verification_token) }}"
        style="padding:10px 20px; background:#4CAF50; color:white; text-decoration:none;">
        Verify Email
    </a>
</body>

</html>
