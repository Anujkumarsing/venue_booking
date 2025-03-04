@extends('admin.layout.app')
@section('title', 'Admin | Manage Users')
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
<div class="content-page">
    <div class="content">
      <div class="container">

      <!-- Page-Title -->
        <div class="row">
            <div class="col-sm-12">
              <h4 class="pull-left page-title">Manage Users</h4>

              <a href="{{ route('save.users') }}" class="rm_new04"><i class="fa fa-plus-circle" aria-hidden="true"></i> Add user</a>
            </div>
          </div>

      <!-- BODY AREA START -->
      <div class="row">
        @include('admin.includes.message')
            <div class="col-md-12">

            <div class="panel panel-default">
            <div class="panel-heading rm02 rm04 rm_new01">
                <form action="{{ route('manage.users') }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label for="calendar">Calendar</label>
                        <select name="calendar_id" class="form-control">
                            <option value="">Select Calendar</option>
                            @foreach($calendars as $calendar)
                                <option value="{{ $calendar->id }}" {{ $selectedCalendar == $calendar->id ? 'selected' : '' }}>
                                    {{ $calendar->calendar_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="rm05">
                        <button class="btn btn-primary waves-effect waves-light w-md" type="submit">Search</button>
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
                          <th>Name </th>
                          <th>Calendar</th>
                          <th>Email</th>
                          <th>Join Date 	</th>
                          <th>Status</th>
                          <th class="rm07 rm_new06">Action</th>
                        </tr>
                      </thead>
                      <tbody>
                        @forelse($users as $user)
                            <tr>
                                <td><strong>{{ $user->fname }} {{ $user->lname }}</strong></td>
                                <td>
                                    {{ $user->calendars->pluck('calendar_name')->join(', ') }}
                                </td>
                                <td>{{ $user->email }}</td>
                                <td>{{ $user->created_at->format('d.m.Y') }}</td>
                                <td>{{ $user->status ? 'Active' : 'Inactive' }}</td>
                                <td class="tablee_acttionn">
                                    @if($user->status == 1)
                                        <a href="{{ route('toggle.status.user', $user->id) }}" class="css-tooltip-top color-blue"
                                           onclick="return confirm('Are you sure you want to deactivate this user?')">
                                            <span>Deactivate</span> <i class="fa fa-ban" aria-hidden="true"></i>
                                        </a>
                                    @else
                                        <a href="{{ route('toggle.status.user', $user->id) }}" class="css-tooltip-top color-blue"
                                           onclick="return confirm('Are you sure you want to activate this user?')">
                                            <span>Activate</span> <i class="fa fa-check" aria-hidden="true"></i>
                                        </a>
                                    @endif
                                    <a href="{{ route('save.users', $user->id) }}" class="css-tooltip-top color-blue">
                                        <span>Edit</span> <i class="fa fa-pencil" aria-hidden="true"></i>
                                    </a>
                                    <a href="{{ route('delete.user', $user->id) }}" class="css-tooltip-top color-blue"
                                       onclick="return confirm('Are you sure you want to delete this user?')">
                                        <span>Delete</span> <i class="fa fa-trash" aria-hidden="true"></i>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6">No users found.</td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                  </div>


                  <div class="pagination-wrapper">
                    {{ $users->links('vendor.pagination.custom') }}
                </div>


                </div>
              </div>
            </div>
            </div>


            </div>
          </div>

      </div>
      <!-- container -->

      </div>

      <!--FOOTER-->
      @include('admin.includes.footer')
  </div>
@endsection
@section('scripts')
@include('admin.includes.scripts')
<script>
	var resizefunc = [];
</script>
@endsection
