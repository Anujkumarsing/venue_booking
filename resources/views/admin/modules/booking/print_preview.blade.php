<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="{{ asset('admin/assets/images/favicon.png') }}">
    <title>Venue Booking Calendar - View Booking</title>

    <link href="{{ asset('admin/assets/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('admin/assets/css/core.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('admin/assets/css/components.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('admin/assets/css/pages3.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('admin/assets/css/menu1.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('admin/assets/css/responsive1.css') }}" rel="stylesheet" type="text/css">

<!--Multiselect box-->
<link rel="stylesheet" href="{{ asset('admin/assets/css/chosen.css') }}" />

<!--date Picker-->
<link rel="stylesheet" href="{{ asset('admin/assets/css/jquery-ui.css') }}">

<!--ICON Font Awesome -->
<link href="{{ asset('admin/assets/css/font-awesome.css') }}" rel="stylesheet" type="text/css">

<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">

</head>
<body class="fixed-left">

<!-- Start wrapper -->
<div id="wrapper">


<!-- Header -->
<div class="topbar">
  <!-- LOGO -->
  <div class="topbar-left top66">
    <div class="text-center"> <a href="#" class="logo"><img src="{{ asset('admin/assets/images/logo.png') }}" alt=""></a> </div>
  </div>
</div>
<!-- Header -->




<!-- Start right side -->
<div class="content-page mmdl">
  <div class="content">
    <div class="container">

	<!-- Page-Title -->
      <div class="row">
          <div class="col-sm-12">
            <h4 class="pull-left page-title">Print Booking</h4>

            <!--<a href="add_students.html" class="rm_new04"><i class="fa fa-plus-circle" aria-hidden="true"></i> Add Students</a>-->
            <a href="#" class="rm_new04"><i class="fa fa-print" aria-hidden="true"></i> Print</a>
          </div>
        </div>

	<!-- BODY AREA START -->
	<div class="row">
          <div class="col-md-12">

	<div class="panel panel-default panel-fill">


        <div class="panel-body">
            @foreach ($bookings as $booking)
            <div class="infoo_r1">
            <div class="about-info-p">
                <strong>User Name</strong>
                <br>
                <p class="text-muted">{{ $booking->user->fname ?? 'N/A' }}</p>
            </div>

            <div class="about-info-p">
                <strong>Calendar</strong>
                <br>
                <p class="text-muted">{{ $booking->calendar->calendar_name ?? '--' }}</p>
            </div>

            <div class="about-info-p">
                <strong>Client 1</strong>
                <br>
                <p class="text-muted">{{ $booking->client_1 ?? 'N/A' }}</p>
            </div>

            <div class="about-info-p m-b-0">
                <strong>Client 1</strong>
                <br>
                <p class="text-muted">{{ $booking->client_2 ?? 'N/A' }}</p>
            </div>

            <div class="about-info-p m-b-0">
                <strong>Email</strong>
                <br>
                <p class="text-muted">{{ $booking->user->email ?? 'N/A' }}</p>
            </div>
            </div>
            <div class="infoo_r1">
            <div class="about-info-p">
                <strong>Phone</strong>
                <br>
                <p class="text-muted">{{ $booking->user->phone ?? 'N/A' }}</p>
            </div>
            <div class="about-info-p">
                <strong>No Of Guest</strong>
                <br>
                <p class="text-muted">{{ $booking->guest_count ?? 'N/A' }}</p>
            </div>
            <div class="about-info-p m-b-0">
                <strong>Booking Date</strong>
                <br>
                <p class="text-muted">{{ $booking->created_at->format('d.m.Y') }}</p>
            </div>
            <div class="about-info-p">
                <strong>Notes</strong>
                <br>
                <p class="text-muted">{{ $booking->notes ?? 'N/A' }}</p>
            </div>
            <div class="about-info-p m-b-0">
                <strong>Uploaded File</strong>
                <br>
                @if ($booking->file)
                <a href="{{ asset('storage/' . $booking->file) }}" target="_blank">View File</a>
            @else
                No file uploaded
            @endif            </div>
            </div>
            <hr>
            @endforeach

        </div>
	</div>

          </div>
        </div>
	<!-- BODY AREA END -->

	</div>
	<!-- container -->

    </div>

    <!--FOOTER-->
</div>
<!-- Start right side -->

</div>
<!-- END wrapper -->


<!-- jQuery  -->
<script src="{{ asset('/admin/assets/js/jquery.min.js') }}"></script>
<script src="{{ asset('/admin/assets/js/bootstrap.min.js') }}"></script>
<script src="{{ asset('/admin/assets/js/custom.js') }}"></script>

<!--mobile left panel open / close-->
<script src="{{ asset('/admin/assets/js/detect.js') }}"></script>
<script src="{{ asset('/admin/assets/js/fastclick.js') }}"></script>
<script src="{{ asset('/admin/assets/js/jquery.slimscroll.js') }}"></script>
<script src="{{ asset('/admin/assets/js/jquery.blockUI.js') }}"></script>
<script>
	var resizefunc = [];
</script>

<script src="{{ asset('/admin/assets/js/jquery.app.js') }}"></script>

<!--date picker-->
<script src="{{ asset('/admin/assets/js/jquery-ui.js') }}"></script>

<!--Multiselect box-->
<script src="{{ asset('/admin/assets/js/chosen.jquery.js') }}"></script>
<script src="{{ asset('/admin/assets/js/init.js') }}"></script>


</body>
</html>
