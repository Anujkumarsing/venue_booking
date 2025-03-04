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
                        <h4 class="pull-left page-title">Manage Sub Admin</h4>
                        <a href="{{ route('save.sub.admin') }}" class="rm_new04"><i class="fa fa-plus-circle" aria-hidden="true"></i> Add Sub Admin</a>
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
                                                      <th>Calendar</th>
                                                      <th>Join Date 	</th>
                                                      <th>Status</th>
                                                      <th class="rm07 rm_new06">Action</th>
                                                    </tr>
                                                  </thead>
                                                  <tbody>
                                                    @foreach (@$subadmins as $subadmin)
                                                        <tr>
                                                            <td><strong>{{ $subadmin->fname }} {{ @$subadmin->lname }}</strong></td>
                                                            <td>{{ @$subadmin->email }}</td>
                                                            <td>
                                                                @foreach (@$subadmin->calendars as $calendar)
                                                                    {{ @$calendar->calendar_name	 }}{{ !$loop->last ? ', ' : '' }}
                                                                @endforeach
                                                            </td>
                                                            <td>{{ @$subadmin->created_at->format('d.m.Y') }}</td>
                                                            <td>{{ @$subadmin->status ? 'Active' : 'Inactive' }}</td>
                                                            <td class="tablee_acttionn">
                                                                @if($subadmin->status == 1)
                                                                    <a href="{{ route('toggle.status.sub.admin', $subadmin->id) }}" class="css-tooltip-top color-blue"
                                                                       onclick="return confirm('Are you sure you want to deactivate this Sub Admin?')">
                                                                        <span>Deactivate</span> <i class="fa fa-ban" aria-hidden="true"></i>
                                                                    </a>
                                                                @else
                                                                    <a href="{{ route('toggle.status.sub.admin', $subadmin->id) }}" class="css-tooltip-top color-blue"
                                                                       onclick="return confirm('Are you sure you want to activate this Sub Admin?')">
                                                                        <span>Activate</span> <i class="fa fa-check" aria-hidden="true"></i>
                                                                    </a>
                                                                @endif
                                                                <a href="{{ route('save.sub.admin', $subadmin->id) }}" class="css-tooltip-top color-blue">
                                                                    <span>Edit</span> <i class="fa fa-pencil" aria-hidden="true"></i>
                                                                </a>
                                                                <a href="{{ route('delete.sub.admin', $subadmin->id) }}" class="css-tooltip-top color-blue"
                                                                   onclick="return confirm('Are you sure you want to delete this Sub Admin?')">
                                                                    <span>Delete</span> <i class="fa fa-trash" aria-hidden="true"></i>
                                                                </a>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>

                                        <!-- Pagination -->
                                        <div class="pagination-wrapper">
                                            {{ $subadmins->links('vendor.pagination.custom') }}
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
