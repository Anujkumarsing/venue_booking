@extends('admin.layout.app')
@section('title', 'Admin | All Bookings')
@section('links')
    @include('admin.includes.links')
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
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
                  <h4 class="pull-left page-title">All Bookings</h4>

                  <a href="{{ route('save.booking') }}" class="rm_new04"><i class="fa fa-plus-circle" aria-hidden="true"></i> Add Booking</a>
                </div>
              </div>

          <!-- BODY AREA START -->
          <div class="row">
            @include('admin.includes.message')
                <div class="col-md-12">

                <div class="panel panel-default">
                     <div class="panel-heading rm02 rm04 rm_new01">
                        <form role="form" action="{{ route('manage.booking') }}" method="post">
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
                                    <option value="all_awaiting" {{ request('status') == 'all_awaiting' ? 'selected' : '' }}>All - Awaiting Approval</option>
                                    <option value="0" {{ request('status') == '0' ? 'selected' : '' }}>New - Awaiting Approval</option>
                                    <option value="3" {{ request('status') == '3' ? 'selected' : '' }}>Edit - Awaiting Approval</option>
                                    <option value="1" {{ request('status') == '1' ? 'selected' : '' }}>Approved</option>
                                    <option value="4" {{ request('status') == '4' ? 'selected' : '' }}>Cancelled</option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="from_date">From Date</label>
                                <input type="text" name="from_date" id="datepicker1" class="form-control" value="{{ request('from_date') }}" placeholder="Select date" autocomplete="off">
                            </div>

                            <div class="form-group">
                                <label for="to_date">To Date</label>
                                <input type="text" name="to_date" id="datepicker" class="form-control" value="{{ request('to_date') }}" placeholder="Select date" autocomplete="off">
                            </div>
                                <div class="rm05">
                                    <button type="submit" class="btn btn-primary">Search</button>
                                    <a href="{{ route('manage.booking') }}" class="btn btn-primary">Reset</a>
                                </div>
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
                              <th>Client</th>
                              <th>Email</th>
                              <th>Calendar</th>
                              <th>Booking Date</th>
                              <th>Status</th>
                              <th class="rm07 rm_new06 ryu">Action</th>
                            </tr>
                          </thead>
                          <tbody>
                            @if($bookings->isEmpty())
                            <tr>
                                <td colspan="6" class="text-center">No data found</td>
                            </tr>
                            @else
                            @foreach(@$bookings as $booking)
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
                                <td class="tablee_acttionn">
                                    @if($booking->booking_status == 0 || $booking->booking_status == 3)
                                        <a href="{{ route('approve.booking', $booking->id) }}" class="css-tooltip-top color-blue" onclick="return confirm('Are you sure you want to approve this booking?')">
                                            <span>Approve</span> <i class="fa fa-check" aria-hidden="true"></i>
                                        </a>

                                        <a href="{{ route('reject.booking', $booking->id) }}" class="css-tooltip-top color-blue" onclick="return confirm('Are you sure you want to reject this booking?')">
                                            <span>Reject</span> <i class="fa fa-eraser" aria-hidden="true"></i>
                                        </a>

                                        <a href="{{ route('save.booking', $booking->id) }}" class="css-tooltip-top color-blue">
                                            <span>Edit</span> <i class="fa fa-pencil" aria-hidden="true"></i>
                                        </a>

                                        <a href="{{ route('view.booking', $booking->id) }}" class="css-tooltip-top color-blue">
                                            <span>View</span> <i class="fa fa-eye" aria-hidden="true"></i>
                                        </a>

                                    @elseif($booking->booking_status == 1)
                                        <a href="{{ route('view.booking', $booking->id) }}" class="css-tooltip-top color-blue">
                                            <span>View</span> <i class="fa fa-eye" aria-hidden="true"></i>
                                        </a>

                                        <a href="{{ route('save.booking', $booking->id) }}" class="css-tooltip-top color-blue">
                                            <span>Edit</span> <i class="fa fa-pencil" aria-hidden="true"></i>
                                        </a>

                                        <a href="{{ route('cancel.booking', $booking->id) }}" class="css-tooltip-top color-blue" onclick="return confirm('Are you sure you want to cancel this booking?')">
                                            <span>Cancel</span> <i class="fa fa-ban" aria-hidden="true"></i>
                                        </a>

                                    @elseif($booking->booking_status == 4)
                                        <a href="{{ route('view.booking', $booking->id) }}" class="css-tooltip-top color-blue">
                                            <span>View</span> <i class="fa fa-eye" aria-hidden="true"></i>
                                        </a>

                                    @else
                                        <a href="{{ route('save.booking', $booking->id) }}" class="css-tooltip-top color-blue">
                                            <span>Edit</span> <i class="fa fa-pencil" aria-hidden="true"></i>
                                        </a>

                                        <a href="{{ route('view.booking', $booking->id) }}" class="css-tooltip-top color-blue">
                                            <span>View</span> <i class="fa fa-eye" aria-hidden="true"></i>
                                        </a>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                            @endif
                        </tbody>
                        </table>
                      </div>


                      {{ @$bookings->appends(request()->except(['page', '_token']))->links('vendor.pagination.custom') }}


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
@endsection
@section('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
@include('admin.includes.scripts')
<script>
    $("#datepicker1").datepicker({
      numberOfMonths: 1,
      onSelect: function (selected) {
        var dt = new Date(selected);
        dt.setDate(dt.getDate() + 1);
        $("#datepicker").datepicker("option", "minDate", dt);
      },
    });
    $("#datepicker").datepicker({
        numberOfMonths: 1,
        changeMonth: true,
        changeYear: true,
        onSelect: function (selected) {
            var dt = new Date(selected);
            dt.setDate(dt.getDate() - 1);
            $("#datepicker1").datepicker("option", "maxDate", null);
        }
    });
</script>

@endsection
