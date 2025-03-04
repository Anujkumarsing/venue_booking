@extends('admin.layout.app')
@section('title', 'Admin | Manage Calendar')
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
                        <h4 class="pull-left page-title">Manage Calendar</h4>

                        <a href="{{ route('save.calendar') }}" class="rm_new04"><i class="fa fa-plus-circle" aria-hidden="true"></i>
                            Add Calendar</a>
                    </div>
                </div>

                <!-- BODY AREA START -->
                <div class="row">
                    @include('admin.includes.message')
                    <div class="col-md-12">
                        <!--Table area start-->
                        <div class="panel panel-default">
                            <div class="panel-body">
                                <div class="row">
                                    <div class="col-md-12 col-sm-12 col-xs-12">
                                        <div class="table-responsive tabbl_styl tabbl_styl_n2">
                                            <table class="table">
                                                <thead>
                                                    <tr>
                                                        <th>Name </th>
                                                        <th>Email</th>
                                                        <th class="rm07 rm_new06">Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach(@$calendars as $calendar)
                                                        @if($calendar->id != 1)
                                                            <tr>
                                                                <td><strong>{{ @$calendar->calendar_name }}</strong></td>
                                                                <td>{{ @$calendar->email }}</td>
                                                                <td class="tablee_acttionn">
                                                                    @if($calendar->status == 1)
                                                                        <a href="{{ route('toggle.calendar.status', $calendar->id) }}" class="css-tooltip-top color-blue"
                                                                           onclick="return confirm('Are you sure you want to deactivate this calendar?')">
                                                                            <span>Active</span> <i class="fa fa-check" aria-hidden="true"></i>
                                                                        </a>
                                                                    @else
                                                                        <a href="{{ route('toggle.calendar.status', $calendar->id) }}" class="css-tooltip-top color-blue"
                                                                           onclick="return confirm('Are you sure you want to activate this calendar?')">
                                                                            <span>Inactive</span> <i class="fa fa-ban" aria-hidden="true"></i>
                                                                        </a>
                                                                    @endif
                                                                    <a href="{{ route('save.calendar', $calendar->id) }}" class="css-tooltip-top color-blue">
                                                                        <span>Edit</span> <i class="fa fa-pencil" aria-hidden="true"></i>
                                                                    </a>
                                                                    <a href="{{ route('delete.calendar', $calendar->id) }}" class="css-tooltip-top color-blue"
                                                                       onclick="return confirm('Are you sure you want to delete this calendar?')">
                                                                        <span>Delete</span> <i class="fa fa-trash" aria-hidden="true"></i>
                                                                    </a>
                                                                </td>
                                                            </tr>
                                                        @endif
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>

                                        <!-- Pagination -->
                                        <div class="pagination-wrapper">
                                            {{ $calendars->links('vendor.pagination.custom') }}
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
    <script>
        var resizefunc = [];
    </script>
@endsection
