@extends('layout.app')
@section('title', 'Users | All Bookings')
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
                  <h4 class="pull-left page-title">All Bookings</h4>

                  <a href="{{ route('user.save.booking') }}" class="rm_new04"><i class="fa fa-plus-circle" aria-hidden="true"></i> Add Booking</a>
                </div>
              </div>

          <!-- BODY AREA START -->
          <div class="row">
            @include('includes.message')
                <div class="col-md-12">
                    <div class="panel panel-default">
                        <div class="panel-heading rm02 rm04 rm_new01">
                           <form role="form" action="{{ route('user.dashboard') }}" method="post">
                               @csrf

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
                                       <a href="{{ route('user.dashboard') }}" class="btn btn-primary">Reset</a>
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
                            @if(@$bookings->isEmpty())
                            <tr>
                                <td colspan="6" class="text-center">No data found</td>
                            </tr>
                            @else
                            @foreach(@$bookings as $booking)
                            <tr>
                                <td><strong>{{ @$booking->client_1 }}</strong></td>
                                <td>{{ @$booking->email }}</td>
                                <td>{{ @$booking->calendar->calendar_name ?? '--' }}</td>
                                <td>{{ @$booking->booking_dt->format('d.m.Y') }}</td>
                                <td>
                                    @switch(@$booking->booking_status)
                                        @case(0) New - Awaiting Approval @break
                                        @case(1) Approved @break
                                        @case(3) Edit - Awaiting Approval @break
                                        @case(2) Rejected @break
                                        @case(4) Cancelled @break
                                        @default Unknown
                                    @endswitch
                                </td>
                                <td class="tablee_acttionn">
                                        <a href="{{ route('user.save.booking', @$booking->id) }}" class="css-tooltip-top color-blue">
                                            <span>Edit</span> <i class="fa fa-pencil" aria-hidden="true"></i>
                                        </a>
                                        <a href="{{ route('user.booking.view', @$booking->id) }}" class="css-tooltip-top color-blue">
                                            <span>View</span> <i class="fa fa-eye" aria-hidden="true"></i>
                                        </a>
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
          @include('includes.footer')
      </div>
    <!-- Start right side -->
</div>
@endsection

@section('scripts')
    @include('includes.scripts')
@endsection
