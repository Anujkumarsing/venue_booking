@extends('admin.layout.app')
@section('title', 'Admin | Approved Bookings')
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
                  <h4 class="pull-left page-title">Approved Bookings</h4>

                  <a href="{{ route('save.booking') }}" class="rm_new04"><i class="fa fa-plus-circle" aria-hidden="true"></i> Add Booking</a>
                </div>
              </div>

          <!-- BODY AREA START -->
          <div class="row">
                <div class="col-md-12">

                <div class="panel panel-default">
                     <div class="panel-heading rm02 rm04 rm_new01">
                        <form role="form" action="{{ route('approved.booking') }}" method="post">
                            @csrf
                            <div class="form-group">
                                <label for="calendar_id">Calendar</label>
                                <select class="form-control rm06" name="calendar_id">
                                    <option value="">Select calendar</option>
                                    @foreach($calendars as $calendar)
                                        <option value="{{ $calendar->id }}" {{ request('calendar_id') == $calendar->id ? 'selected' : '' }}>
                                            {{ $calendar->calendar_name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="rm05">
                                <button class="btn btn-primary waves-effect waves-light w-md" type="submit">Search</button>
                                <a href="{{ route('approved.booking') }}" class="btn btn-secondary waves-effect waves-light w-md">Reset</a>
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
                              <th class="rm07 rm_new06">Action</th>
                            </tr>
                          </thead>
                          <tbody>
                            @if($bookings->isEmpty())
                            <tr>
                                <td colspan="6" class="text-center">No data found</td>
                            </tr>
                        @else
                            @foreach($bookings as $booking)
                            <tr>
                                <td><strong>{{ $booking->client_1 }}</strong></td>
                                <td>{{ $booking->email }}</td>
                                <td>{{ $booking->calendar->calendar_name }}</td>
                                <td>{{ $booking->created_at->format('d.m.Y') }}</td>
                                <td>
                                    @switch($booking->booking_status)
                                        @case(1) Approved @break
                                        @default Unknown
                                    @endswitch
                                </td>
                                <td class="tablee_acttionn">
                                    <a href="{{ route('approved.view.booking', $booking->id) }}" class="css-tooltip-top color-blue">
                                        <span>View</span> <i class="fa fa-eye" aria-hidden="true"></i>
                                    </a>
                                    <a href="{{ route('save.booking', $booking->id) }}" class="css-tooltip-top color-blue">
                                        <span>Edit</span> <i class="fa fa-pencil" aria-hidden="true"></i>
                                    </a>
                                    <a href="{{ route('cancel.booking', $booking->id) }}" class="css-tooltip-top color-blue" onclick="return confirm('Are you sure you want to cancel this booking?')">
                                        <span>Cancel</span> <i class="fa fa-ban" aria-hidden="true"></i>
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                            @endif
                          </tbody>
                        </table>
                      </div>
                      <div class="pagination-wrapper text-center">
                        {{ $bookings->links('vendor.pagination.custom') }}
                    </div>
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
          @include('admin.includes.footer')
      </div>
    <!-- Start right side -->
</div>
@endsection

@section('scripts')
    @include('admin.includes.scripts')
@endsection
