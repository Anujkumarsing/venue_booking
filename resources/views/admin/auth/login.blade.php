<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="shortcut icon" href="{{ asset('admin/assets/images/favicon.png') }}">

    <title>Venue Booking | Login</title>

<link href="{{ asset('admin/assets/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css">
<link href="{{ asset('admin/assets/css/core.css') }}" rel="stylesheet" type="text/css">
<link href="{{ asset('admin/assets/css/components.css') }}" rel="stylesheet" type="text/css">
<link href="{{ asset('admin/assets/css/pages3.css') }}" rel="stylesheet" type="text/css">
<link href="{{ asset('admin/assets/css/menu1.css') }}" rel="stylesheet" type="text/css">
<link href="{{ asset('admin/assets/css/responsive1.css') }}" rel="stylesheet" type="text/css">

<!--ICON Font Awesome -->
<link href="{{ asset('admin/assets/css/font-awesome.css') }}" rel="stylesheet" type="text/css">

<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
<style>
    .text-danger {
        color: #ff0000;
    }
    .is-invalid {
        border-color: #ff0000;
    }
</style>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.5/jquery.validate.min.js"></script>
<script src="your-custom-script.js"></script>
</head>
<body class="login_b1_bg">
    <div class="wrapper-page">
        <div class="panel panel-color panel-primary panel-pages login_b1">

            <div class="panel-heading rm08">
                <img src="{{ asset('public/admin/assets/images/logo.png') }}" alt="">
                <span><i class="fa fa-key" aria-hidden="true"></i><strong></strong></span>
                <div class="lln"></div>
            </div>
          @if ($errors->any())
    <div class="alert alert-danger alert-dismissible login-alert" style="text-align: center;">
        <button type="button" class="close close-alert" aria-label="Close">&times;</button>
        <ul>
            @foreach ($errors->all() as $error)
            <li style="list-style: none;">{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    @if(session()->has('success'))
    <div class="alert alert-success alert-dismissible login-alert" style="text-align: center;">
        <button type="button" class="close close-alert" aria-label="Close">&times;</button>
        {{ session()->get('success') }}
    </div>
    @endif

    @if(session()->has('error'))
    <div class="alert alert-danger alert-dismissible login-alert" style="text-align: center;">
        <button type="button" class="close close-alert" aria-label="Close">&times;</button>
        {!! session()->get('error') !!}
    </div>
    @endif

         <form id="admin-login-form" class="form-horizontal m-t-20" method="POST" action="{{ route('admin.login.submit') }}">
        @csrf
        <div class="form-group">
            <label>Email</label>
            <input class="form-control input-lg" type="email" name="email" placeholder="Email Address"
                   value="{{ old('email') }}">
        </div>

        <div class="form-group">
            <label>Password</label>
            <input class="form-control input-lg" name="password" type="password" placeholder="Password">
        </div>

        <div class="form-group">
            <div class="checkbox checkbox-primary">
                <input id="checkbox-signup" type="checkbox" name="remember">
                <label for="checkbox-signup">
                    Remember me
                </label>
            </div>
        </div>

        <div class="form-group text-center m-t-40 lc_color">
            <button class="btn btn-primary btn-lg w-lg waves-effect waves-light rm01" type="submit">Login</button>
        </div>

        <div class="form-group m-t-30">
            <a href="{{ route('admin.forgot.password') }}" class="rm01 rtyya"><i class="fa fa-lock m-r-5"></i> Forgot your password?</a>
        </div>
         </form>

    </div>
</div>
<script>
    $(document).ready(function () {
        $("#admin-login-form").validate({
            rules: {
                email: {
                    required: true,
                    email: true
                },
                password: {
                    required: true,
                }
            },
            messages: {
                email: {
                    required: "Please enter your email address.",
                    email: "Please enter a valid email address."
                },
                password: {
                    required: "Please provide a password.",
                }
            },
            errorElement: "span",
            errorClass: "text-danger",
            highlight: function (element) {
                $(element).addClass("is-invalid");
            },
            unhighlight: function (element) {
                $(element).removeClass("is-invalid");
            }
        });
    });
</script>
</body>
</html>
