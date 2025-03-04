<!DOCTYPE html>
<html>
<head>
    <title>User Login Info</title>
</head>
<body>
    <h1>Welcome, {{ $user->fname }} {{ $user->lname }}!</h1>
    <p>Your account has been created successfully. Below are your login credentials:</p>
    <p><strong>Email:</strong> {{ $user->email }}</p>
    <p><strong>Password:</strong> {{ $password }}</p>
    <p>Use these credentials to log in to your account by clicking the link below:</p>
    <p><a href="{{ $loginUrl }}">Login to your account</a></p>
    <br>
    <p>Thank you,</p>
    <p>The Admin Team</p>
</body>
</html>
