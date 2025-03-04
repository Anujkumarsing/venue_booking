<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="{{ asset('admin/assets/images/favicon.png') }}">
    <title>Admin</title>

    <link href="{{ asset('admin/assets/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('admin/assets/css/core.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('admin/assets/css/components.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('admin/assets/css/pages3.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('admin/assets/css/menu1.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('admin/assets/css/responsive1.css') }}" rel="stylesheet" type="text/css">

    <!--ICON Font Awesome -->
    <link href="{{ asset('admin/assets/css/font-awesome.css') }}" rel="stylesheet" type="text/css">

</head>
<body>

<div class="wrapper-page">
    <div class="panel panel-color panel-primary panel-pages login_b1">

        <div class="panel-heading rm08">
            <img src="{{ asset('admin/assets/images/logo.png') }}" alt="">
            <span><i class="fa fa-lock" aria-hidden="true"></i><strong></strong></span>
            <div class="lln"></div>
        </div>

        @if ($errors->any())
        <div class="alert alert-danger alert-dismissible login-alert" style="text-align: center;">
            <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
            <ul>
                @foreach ($errors->all() as $error)
                <li style="list-style: none;">{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        @if(session()->has('success'))
        <div class="alert alert-success alert-dismissible login-alert" style="text-align: center;">
            <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
            {{ session()->get('success') }}
        </div>
        @endif

        @if(session()->has('error'))
        <div class="alert alert-danger alert-dismissible login-alert" style="text-align: center;">
            <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
            {!! session()->get('error') !!}
        </div>
        @endif

        <form class="form-horizontal m-t-20" action="{{ route('admin.forgot.password.post') }}" method="post" target="_blank">
            @csrf
            <div class="form-group">
            		<h1 class="text-center">Forgot your password?</h1>
                	<label class="text-center"> Enter your Email and instructions will be sent to you! </label>
            </div>

            <div class="form-group">
                	<label>Email</label>
                <input type="email" name="email" class="form-control" placeholder="Enter your email" required>
            </div>
            <div class="form-group text-center m-t-40 lc_color">
                   <a> <button class="btn btn-primary btn-lg w-lg waves-effect waves-light rm01" type="submit">Submit</button></a>
            </div>
        </form>
    </div>
</div>
</body>
</html>
