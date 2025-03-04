@extends(Auth::guard('admin')->check() && (Auth::guard('admin')->user()->user_type_id == 1 || Auth::guard('admin')->user()->user_type_id == 2)
    ? 'admin.layout.app'
    : (Auth::guard('user')->check() && Auth::guard('user')->user()->user_type_id == 3
        ? 'layout.app'
        : abort(403)))

@section('title', Auth::guard('admin')->check() && (Auth::guard('admin')->user()->user_type_id == 1 || Auth::guard('admin')->user()->user_type_id == 2)
    ? 'Admin | Change Password'
    : (Auth::guard('user')->check() && Auth::guard('user')->user()->user_type_id == 3
        ? 'User | Change Password'
        : ''))

@section('links')
    @if(Auth::guard('admin')->check() && (Auth::guard('admin')->user()->user_type_id == 1 || Auth::guard('admin')->user()->user_type_id == 2))
        @include('admin.includes.links')
    @elseif(Auth::guard('user')->check() && Auth::guard('user')->user()->user_type_id == 3)
        @include('includes.links')
    @endif
@endsection



@section('content')
    @if(Auth::guard('admin')->check() && (Auth::guard('admin')->user()->user_type_id == 1 || Auth::guard('admin')->user()->user_type_id == 2))
        @include('admin.includes.header')
        @include('admin.includes.leftsidemenubar')
    @elseif(Auth::guard('user')->check() && Auth::guard('user')->user()->user_type_id == 3)
        @include('includes.header')
        @include('includes.leftsidemenubar')
    @endif

    <div id="wrapper">
        <div class="content-page">
            <div class="content">
                <div class="container">
                   @if(Auth::guard('admin')->check())
                    @include('admin.includes.message')
                    @elseif(Auth::guard('user')->check())
                            @include('includes.message')
                           @endif
                    <div class="row">
                        <div class="col-sm-12">
                            <h4 class="pull-left page-title mob_ad1">Change Password</h4>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="panel panel-default panel-fill">
                                <div class="panel-body rm02 rm04">
                                    <div class="nst_dsgg">
                                        <form method="POST" action="{{ route('change.password') }}">
                                            @csrf

                                            <div class="col-md-6 col-sm-6 mb_01">
                                                <label>Current Password <strong>*</strong></label>
                                                <input type="password" name="current_password" class="form-control" required>
                                                @error('current_password')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>

                                            <div class="col-md-6 col-sm-6 mb_01">
                                                <label>New Password <strong>*</strong></label>
                                                <input type="password" name="new_password" class="form-control" required>
                                                @error('new_password')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>

                                            <div class="col-md-6 col-sm-6 mb_01">
                                                <label>Confirm New Password <strong>*</strong></label>
                                                <input type="password" name="new_password_confirmation" class="form-control" required>
                                                @error('new_password_confirmation')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>

                                            <div class="clearfix"></div>

                                            <div class="col-lg-12 text-center">
                                                <div class="qroo11">
                                                    <button class="btn btn-primary waves-effect waves-light w-md qroo" type="submit">Save Changes</button>
                                                </div>
                                            </div>

                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
            @if(Auth::guard('admin')->check() && (Auth::guard('admin')->user()->user_type_id == 1 || Auth::guard('admin')->user()->user_type_id == 2))
            @include('admin.includes.footer')
        @endif
        </div>
    </div>
@endsection

@section('scripts')
    @if(Auth::guard('admin')->check() && (Auth::guard('admin')->user()->user_type_id == 1 || Auth::guard('admin')->user()->user_type_id == 2))
        @include('admin.includes.scripts')
    @elseif(Auth::guard('user')->check() && Auth::guard('user')->user()->user_type_id == 3)
        @include('includes.scripts')
    @endif
@endsection


