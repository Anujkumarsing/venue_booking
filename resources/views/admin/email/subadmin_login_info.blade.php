<!DOCTYPE html>
<html>
<head>
    <title>Sub-Admin Login Info</title>
</head>
<body>
    <h1>Welcome, {{ $subAdmin->fname }} {{ $subAdmin->lname }}!</h1>
    <p>Your sub-admin account has been created successfully. Below are your login credentials:</p>
    <p><strong>Email:</strong> {{ $subAdmin->email }}</p>
    <p><strong>Password:</strong> {{ $password }}</p>
    <p>Use these credentials to log in to your account by clicking the link below:</p>
    <p><a href="{{ $loginUrl }}">Login to your account</a></p>
    <br>
    <p>Thank you,</p>
    <p>The Admin Team</p>
</body>
</html>
