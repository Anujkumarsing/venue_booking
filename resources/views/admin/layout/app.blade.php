<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="apple-touch-icon-precomposed" sizes="144x144" href="{{asset('public/admin/assets/ico/apple-touch-icon-144-precomposed.png')}}">
    <link rel="apple-touch-icon-precomposed" sizes="114x114" href="{{asset('public/admin/assets/ico/apple-touch-icon-114-precomposed.png')}}">
    <link rel="apple-touch-icon-precomposed" sizes="72x72" href="{{asset('public/admin/assets/ico/apple-touch-icon-72-precomposed.png')}}">
    <link rel="shortcut icon" href="{{asset('public/admin/assets/images/favicon.png')}}">
    <title>Venue Booking Calendar | @yield('title')</title>

    @yield('links')
  </head>
<body>
    @yield('header')
    @yield('leftsidemenubar')
    @yield('content')
    @yield('footer')
    @yield('scripts')
</body>
</html>
