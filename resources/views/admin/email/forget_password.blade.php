<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Admin | Reset Password</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f7f7f7;
        }

        .email-container {
            max-width: 600px;
            margin: 50px auto;
            background-color: #ffffff;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .email-header {
            background-color: #4caf50;
            color: #ffffff;
            padding: 20px;
            text-align: center;
        }

        .email-header h1 {
            margin: 0;
            font-size: 24px;
        }

        .email-body {
            padding: 20px 30px;
            text-align: center;
            color: #333333;
        }

        .email-body p {
            font-size: 16px;
            margin: 20px 0;
            line-height: 1.5;
        }

        .reset-button {
            display: inline-block;
            padding: 10px 20px;
            background-color: #4caf50;
            color: #ffffff;
            text-decoration: none;
            font-size: 16px;
            border-radius: 5px;
            margin: 20px 0;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
        }

        .reset-button:hover {
            background-color: #45a049;
        }

        .email-footer {
            background-color: #f1f1f1;
            padding: 10px;
            text-align: center;
            font-size: 14px;
            color: #777777;
        }

        .email-footer a {
            color: #4caf50;
            text-decoration: none;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <!-- Header Section -->
        <div class="email-header">
            <h1>Reset Your Password</h1>
        </div>

        <!-- Body Section -->
        <div class="email-body">
            <p>Hello,</p>
            <p>You requested to reset your password. Click the button below to reset it:</p>
            <a href="{{ route('admin.reset.password', $token) }}" class="reset-button">Reset Password</a>
            <p>If you did not request this, you can safely ignore this email.</p>
        </div>

        <!-- Footer Section -->
        <div class="email-footer">
            <p>Need help? <a href="#">Contact Support</a></p>
            <p>&copy; {{ date('Y') }} Admin Panel. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
