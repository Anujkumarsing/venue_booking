@extends('admin.layout.app')
@section('title', 'Admin | Awaiting Approval Bookings')
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
                  <h4 class="pull-left page-title rsfull">Awaiting Approval</h4>

                  <ol class="breadcrumb pull-right previeww rsfull002">
                    <li><a href="{{ route('booking_print') }}"><i class="fa fa-print" aria-hidden="true"></i> Print preview</a></li>
                  </ol>

                  <a href="{{ route('save.booking') }}" class="rm_new04"><i class="fa fa-plus-circle" aria-hidden="true"></i> Add Booking</a>
                </div>
              </div>

          <!-- BODY AREA START -->
          <div class="row">
            @include('admin.includes.message')
                <div class="col-md-12">

                <div class="panel panel-default">
                     <div class="panel-heading rm02 rm04 rm_new01">
                        <form role="form" action="{{ route('awaiting.approval') }}" method="post">
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
                                <a href="{{ route('awaiting.approval') }}" class="btn btn-secondary waves-effect waves-light w-md">Reset</a>
                            </div>
                        </form>
                    </div>
                </div>

                <!--Table area start-->
                <div class="panel panel-default">
                <div class="panel-body">
                  <div class="row">
                    <div class="col-md-12 col-sm-12 col-xs-12">
                        <div class="panel panel-default bottom_nsc">
                            <div class="panel-heading rm02 rm04 rm_new01">
                                <ol class="breadcrumb pull-right previeww">
                                    <li><a href="#" id="selectAllButton">Select All</a></li>
                                    <li><a href="#" id="deselectAllButton">Deselect All</a></li>
                                </ol>
                                <form role="form" id="bulkActionForm">
                                    <div class="form-group">
                                        <select class="form-control" id="bulkAction" name="action">
                                            <option value="">Select Action</option>
                                            <option value="approve">Approve</option>
                                            <option value="reject">Reject</option>
                                        </select>
                                    </div>
                                    <button class="btn btn-primary waves-effect waves-light w-md" type="submit">Go</button>
                                </form>
                            </div>
                        </div>
                      <div class="table-responsive tabbl_styl tabbl_styl_n2">
                        <table class="table">
                          <thead>
                            <tr>
                                <th>&nbsp;</th>
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
                            @foreach(@$bookings as $booking)
                                <tr>
                                    <td>
                                        <div class="checkbox checkbox-primary">
                                            <input type="checkbox" class="selectCheckbox" name="booking_ids[]" value="{{ $booking->id }}">
                                            <label for="checkbox-{{ @$booking->id }}">&nbsp;</label>
                                        </div>
                                    </td>
                                    <td><strong>{{ @$booking->client_1 }}</strong></td>
                                    <td>{{ @$booking->email }}</td>
                                    <td>{{ @$booking->calendar->calendar_name }}</td>
                                    <td>{{ @$booking->created_at->format('d.m.Y') }}</td>
                                    <td>
                                        @switch(@$booking->booking_status)
                                            @case(0) New - Awaiting Approval @break
                                            @case(3) Edit - Awaiting Approval @break
                                            @default Unknown
                                        @endswitch
                                    </td>
                                    <td class="tablee_acttionn">
                                        <a href="{{ route('approve.booking', @$booking->id) }}" class="css-tooltip-top color-blue">
                                            <span>Approve</span> <i class="fa fa-check" aria-hidden="true"></i>
                                        </a>
                                        <a href="{{ route('reject.booking', @$booking->id) }}" class="css-tooltip-top color-blue">
                                            <span>Reject</span> <i class="fa fa-ban" aria-hidden="true"></i>
                                        </a>
                                        <a href="{{ route('awaiting.view.booking', @$booking->id) }}" class="css-tooltip-top color-blue">
                                            <span>View</span> <i class="fa fa-eye" aria-hidden="true"></i>
                                        </a>
                                        <a href="{{ route('save.booking', @$booking->id) }}" class="css-tooltip-top color-blue">
                                            <span>Edit</span> <i class="fa fa-pencil" aria-hidden="true"></i>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                            @endif
                        </tbody>
                        </table>
                      </div>

                      {{ @$bookings->appends(request()->except(['page', '_token']))->links('vendor.pagination.custom') }}
                  {{-- <div class="pagination-wrapper">
                    {{ $bookings->links('vendor.pagination.custom') }}
                </div> --}}

                      {{-- <div class="panel panel-default bottom_nsc">
                        <div class="panel-heading rm02 rm04 rm_new01">
                            <ol class="breadcrumb pull-right previeww">
                                <li><a href="#" id="selectAllButton">Select All</a></li>
                                <li><a href="#" id="deselectAllButton">Deselect All</a></li>
                            </ol>
                            <form role="form" id="bulkActionForm">
                                <div class="form-group">
                                    <select class="form-control" id="bulkAction" name="action">
                                        <option value="">Select Action</option>
                                        <option value="approve">Approve</option>
                                        <option value="reject">Reject</option>
                                    </select>
                                </div>
                                <button class="btn btn-primary waves-effect waves-light w-md" type="submit">Go</button>
                            </form>
                        </div>
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

          <!--FOOTER-->
          @include('admin.includes.footer')
      </div>
    <!-- Start right side -->
</div>
@endsection

@section('scripts')
    @include('admin.includes.scripts')
    <script>
        $(document).ready(function () {
            $('#selectAllButton').click(function (e) {
                e.preventDefault();
                $('.selectCheckbox').prop('checked', true);
            });

            $('#deselectAllButton').click(function (e) {
                e.preventDefault();
                $('.selectCheckbox').prop('checked', false);
            });

            $('#bulkActionForm').on('submit', function (e) {
    e.preventDefault();

    let selectedIds = [];
    $('.selectCheckbox:checked').each(function () {
        selectedIds.push($(this).val());
    });

    if (selectedIds.length === 0) {
        alert('No bookings selected!');
        return;
    }

    let action = $('#bulkAction').val();
    if (!action) {
        alert('Please select an action!');
        return;
    }

                $.ajax({
                    url: "{{ route('bulk.action') }}",
                    type: "POST",
                    data: {
                        _token: "{{ csrf_token() }}",
                        booking_ids: selectedIds,
                        action: action
                    },
                    success: function (response) {
                        alert(response.message);
                        location.reload();
                    },
                    error: function (xhr) {
                        alert(xhr.responseJSON.message || 'An error occurred!');
                    }
                });
            });
        });
    </script>
@endsection
