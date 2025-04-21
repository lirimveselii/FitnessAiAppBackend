<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Password Reset</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            padding: 20px;
        }

        .container {
            width: 100%;
            max-width: 400px;
            margin: auto;
            background-color: #fff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        input {
            width: 100%;
            padding: 10px;
            margin-bottom: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }

        button {
            width: 100%;
            padding: 12px;
            background-color: #4A90E2;
            color: white;
            border: none;
            border-radius: 4px;
            font-size: 16px;
            cursor: pointer;
        }

        button:hover {
            background-color: #357ABD;
        }
    </style>
</head>

<body>
    <div class="container">
        <h2>Reset Your Password</h2>
        <form action="{{ route('password.update') }}" method="POST">
            @csrf
            <input type="hidden" name="token" value="{{ $reset_token }}">
            <input type="hidden" name="email" value="{{ $user_email }}">

            <label for="password">New Password</label>
            <input type="text" id="password" name="password" required>

            <label for="password_confirmation">Confirm Password</label>
            <input type="text" id="password_confirmation" name="password_confirmation" required>

            <button type="submit">Reset Password</button>
        </form>
    </div>
</body>

</html>
