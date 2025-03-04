@extends('admin.layout.app')
@section('title', 'Admin | Booking Report')
@section('links')
    @include('admin.includes.links')

@endsection
@section('content')

@section('header')
    @include('admin.includes.header')
@endsection

@section('leftsidemenubar')
    @include('admin.includes.leftsidemenubar')
@endsection
<div id="wrapper">

    <div class="content-page">
        <div class="content">
          <div class="container">

          <!-- Page-Title -->
          <div class="row">
            <div class="col-sm-12">
              <h4 class="pull-left page-title mob_ad1">Booking Report</h4>

              <ol class="breadcrumb pull-right previeww">
                <li><a href="javascript void(0)" data-toggle="modal" data-target="#myModal"><i class="fa fa-print" aria-hidden="true"></i> Print preview</a></li>
              </ol>


            </div>
          </div>

          <!-- BODY AREA START -->
          <div class="row">
            @include('admin.includes.message')
                <div class="col-md-12">

                <div class="panel panel-default">
                     <div class="panel-heading rm02 rm04 rm_new01">
                        <form role="form" action="{{ route('booking.report') }}" method="post">
                            @csrf
                            <div class="form-group">
                                <label for="calendar_id">Calendar</label>
                                <select class="form-control" name="calendar_id">
                                    <option value="">Select calendar</option>
                                    @foreach($calendars as $calendar)
                                        <option value="{{ $calendar->id }}" {{ request('calendar_id') == $calendar->id ? 'selected' : '' }}>
                                            {{ $calendar->calendar_name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="status">Status</label>
                                <select class="form-control" name="status">
                                    <option value="">Select status</option>
                                    <option value="0" {{ request('status') == '0' ? 'selected' : '' }}>New - Awaiting Approval</option>
                                    <option value="1" {{ request('status') == '1' ? 'selected' : '' }}>Approved</option>
                                    <option value="4" {{ request('status') == '4' ? 'selected' : '' }}>Cancelled</option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="from_date">From Date</label>
                                <input type="text" name="from_date" id="datepicker1" class="form-control" value="{{ request('from_date') }}" placeholder="Select">
                            </div>

                            <div class="form-group">
                                <label for="to_date">To Date</label>
                                <input type="text" name="to_date" id="datepicker" class="form-control" value="{{ request('to_date') }}" placeholder="Select ">
                            </div>

                            {{-- <div class="rm05"> --}}
                                <button type="submit" class="btn btn-primary">Search</button>
                                <a href="{{ route('booking.report') }}" class="btn btn-primary">Reset</a>
                                  {{-- </div> --}}
                        </form>

                    </div>
                </div>

                <!--Table area start-->
                <div class="panel panel-default">
                <div class="panel-body">
                  <div class="row">
                    <div class="col-md-12 col-sm-12 col-xs-12">
                      <div class="table-responsive tabbl_styl tabbl_styl_n2">
                        <table class="table">
                          <thead>
                            <tr>
                              <th class="row_width_1">Client</th>
                              <th class="row_width_2">Email</th>
                              <th class="row_width_3">Calendar</th>
                              <th class="row_width_4">Booking Date</th>
                              <th class="row_width_4">Status</th>
                            </tr>
                          </thead>
                          <tbody class="rrmm002">
                            @if($bookings->isEmpty())
                            <tr>
                                <td colspan="6" class="text-center">No data found</td>
                            </tr>
                            @else
                            @foreach($bookings as $booking)
                            <tr>
                                <td><strong>{{ $booking->client_1 }}</strong></td>
                                <td>{{ Str::limit($booking->email, 25, '...') }}</td>
                                <td>{{ $booking->calendar->calendar_name ?? '--' }}</td>
                                <td>{{ $booking->booking_dt->format('d.m.Y') }}</td>
                                <td>
                                    @switch($booking->booking_status)
                                        @case(0) New - Awaiting Approval @break
                                        @case(1) Approved @break
                                        @case(3) Edit - Awaiting Approval @break
                                        @case(2) Rejected @break
                                        @case(4) Cancelled @break
                                        @default Unknown
                                    @endswitch
                                </td>
                            </tr>
                            @endforeach
                            @endif
                        </tbody>
                        </table>
                      </div>

                      {{-- <div class="pagination-wrapper text-center">
                        {{ @$bookings->links('vendor.pagination.custom') }}
                    </div> --}}

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
          @include('admin.includes.footer')
      </div>
    <!-- Start right side -->
</div>
<div class="modal fade rm_popup" id="myModal" role="dialog">
    <div class="modal-dialog">

      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <a href="javascript:void(0);" onclick="printModalContent()"><i class="fa fa-print" aria-hidden="true"></i> Print</a></li>
        </div>

        <div class="modal-body">
          <div class="panel-body">
            <div class="row">
              <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="table-responsive tabbl_styl tabbl_styl_n2">
                  <table class="table">
                    <thead>
                      <tr>
                        <th>Client</th>
                        <th>Email</th>
                        <th>Calendar</th>
                        <th>Booking Date</th>
                        <th>Status</th>
                      </tr>
                    </thead>
                    <tbody>
                        @foreach($bookings as $booking)
                          <tr>
                              <td><strong>{{ $booking->client_1 }}</strong></td>
                              <td>{{ $booking->email }}</td>
                              <td>{{ $booking->calendar->calendar_name ?? '--' }}</td>
                              <td>{{ $booking->booking_dt->format('d.m.Y') }}</td>
                              <td>
                                  @switch($booking->booking_status)
                                      @case(0) New - Awaiting Approval @break
                                      @case(1) Approved @break
                                      @case(3) Edit - Awaiting Approval @break
                                      @case(2) Rejected @break
                                      @case(4) Cancelled @break
                                      @default Unknown
                                  @endswitch
                              </td>
                          </tr>
                        @endforeach
                      </tbody>
                  </table>
                </div>
              </div>
            </div>
          </div>
        </div>

      </div>

    </div>
  </div>
@endsection

@section('scripts')
    @include('admin.includes.scripts')
    <script>
        function printModalContent() {
    var printContent = document.querySelector("#myModal .modal-body").innerHTML;
    var modal = document.getElementById("myModal");

    modal.style.display = "none";

    var originalContent = document.body.innerHTML;
    document.body.innerHTML = printContent;

    window.print();

    document.body.innerHTML = originalContent;
    location.reload();
}
        </script>
@endsection
