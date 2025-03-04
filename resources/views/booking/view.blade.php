@extends('layout.app')
@section('title', 'User | View Bookings')
@section('links')
    @include('includes.links')
@endsection
@section('content')

@section('header')
    @include('includes.header')
@endsection

@section('leftsidemenubar')
    @include('includes.leftsidemenubar')
@endsection
<div id="wrapper">

    <div class="content-page">
        <div class="content">
          <div class="container">

          <!-- Page-Title -->
            <div class="row">
                <div class="col-sm-12">
                  <h4 class="pull-left page-title">View Booking</h4>

                  <!--<a href="add_students.html" class="rm_new04"><i class="fa fa-plus-circle" aria-hidden="true"></i> Add Students</a>-->
                  <a href="{{ route('user.dashboard') }}" class="rm_new04"><i class="fa fa-chevron-left arsg" aria-hidden="true"></i> Back</a>
                </div>
              </div>

          <!-- BODY AREA START -->
          <div class="row">
                <div class="col-md-12">

          <div class="panel panel-default panel-fill">
              <div class="panel-heading title_style">
                  <h3 class="panel-title">Booking Information</h3>
                  <ol class="breadcrumb pull-right">
                    <li><a href="#"><i class="fa fa-pencil" aria-hidden="true"></i> Edit</a></li>
                    <li><a href="#"><i class="fa fa-times" aria-hidden="true"></i> Cancel</a></li>
                  </ol>
              </div>

              <div class="panel-body">

                  <div class="infoo_r1 for_ttxt_clorr">
                  <div class="about-info-p">
                      <strong>User Name</strong>
                      <br>
                      <p class="text-muted"> {{ $booking->user->fname ?? 'N/A' }} {{ $booking->user->lname ?? '' }}</p>
                    </div>
                    <div class="about-info-p">
                        <strong>Status</strong>
                        <br>
                        <p class="text-muted">
                                         @switch($booking->booking_status)
                                        @case(0) New - Awaiting Approval @break
                                        @case(1) Approved @break
                                        @case(3) Edit - Awaiting Approval @break
                                        @case(2) Rejected @break
                                        @case(4) Cancelled @break
                                        @default Unknown
                                    @endswitch
                        </p>
                    </div>
                  <div class="about-info-p">
                      <strong>Calendar</strong>
                      <br>
                      <p class="text-muted">{{ $booking->calendar->calendar_name ?? '--' }}</p>
                  </div>

                  <div class="about-info-p">
                      <strong>Client 1</strong>
                      <br>
                      <p class="text-muted">{{ $booking->client_1 }}</p>
                  </div>

                  <div class="about-info-p m-b-0">
                      <strong>Client 2</strong>
                      <br>
                      <p class="text-muted">{{ $booking->client_2 }}</p>
                  </div>

                  <div class="about-info-p m-b-0">
                      <strong>Email</strong>
                      <br>
                      <p class="text-muted">{{ $booking->email }}</p>
                  </div>
                  </div>
                  <div class="infoo_r1 for_ttxt_clorr">
                  <div class="about-info-p">
                      <strong>Phone</strong>
                      <br>
                      <p class="text-muted">{{ $booking->phone }}</p>
                  </div>
                  <div class="about-info-p">
                      <strong>No Of Guest</strong>
                      <br>
                      <p class="text-muted">{{ $booking->guest_count }}</p>
                  </div>
                  <div class="about-info-p m-b-0">
                      <strong>Booking Date</strong>
                      <br>
                      <p class="text-muted">{{ $booking->created_at->format('d.m.Y') }}</p>
                  </div>
                  <div class="about-info-p">
                      <strong>Notes</strong>
                      <br>
                      <p class="text-muted">{{ $booking->notes }}</p>
                  </div>
                  <div class="about-info-p m-b-0">
                    <strong>Uploaded File</strong>
                    <br>
                    @if($booking->file && file_exists(public_path('booking/' . $booking->file)))
                        @php
                            $filePath = asset('booking/' . $booking->file);
                            $fileExtension = pathinfo($booking->file, PATHINFO_EXTENSION);
                        @endphp

                        <p class="text-muted">
                            <strong>File Name:</strong> {{ $booking->file }}

                            @if(in_array($fileExtension, ['jpg', 'jpeg', 'png']))
                                <br>
                                <img src="{{ $filePath }}" alt="Uploaded Image" style="max-width: 100px; max-height: 100px;">
                                <br>
                                <a href="{{ $filePath }}" download class="btn btn-primary btn-sm mt-2">Download Image</a>
                            @elseif($fileExtension == 'pdf')
                                <br>
                                <a href="{{ $filePath }}" target="_blank" class="btn btn-primary btn-sm mt-2">View PDF</a>
                                <a href="{{ $filePath }}" download class="btn btn-primary btn-sm mt-2">Download PDF</a>
                            @else
                                <br>
                                <a href="{{ $filePath }}" target="_blank" class="btn btn-primary btn-sm mt-2">Download File</a>
                            @endif
                        </p>
                    @else
                        <p class="text-muted">No file uploaded or file not found.</p>
                    @endif
                </div>
                  </div>
              </div>
          </div>

                </div>
              </div>
          <!-- BODY AREA END -->

          </div>
          <!-- container -->

          </div>

          <!--FOOTER-->
          @include('includes.footer')
      </div>
    <!-- Start right side -->

</div>
@endsection

@section('scripts')
    @include('includes.scripts')
    <script>
        var resizefunc = [];
    </script>
@endsection
