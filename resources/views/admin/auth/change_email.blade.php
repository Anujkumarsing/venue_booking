@extends(Auth::guard('admin')->check() && (Auth::guard('admin')->user()->user_type_id == 1 || Auth::guard('admin')->user()->user_type_id == 2)
    ? 'admin.layout.app'
    : (Auth::guard('user')->check() && Auth::guard('user')->user()->user_type_id == 3
        ? 'layout.app'
        : abort(403)))

@section('title', Auth::guard('admin')->check() && (Auth::guard('admin')->user()->user_type_id == 1 || Auth::guard('admin')->user()->user_type_id == 2)
    ? 'Admin | Change Email'
    : (Auth::guard('user')->check() && Auth::guard('user')->user()->user_type_id == 3
        ? 'User | Change Email'
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
                    <div class="row">
                        <div class="col-sm-12">
                            <h4 class="pull-left page-title">Change Email</h4>
                        </div>
                    </div>

                    <!-- Display Message -->

                    <!-- Change Email Form -->
                    <div class="row">
                        @if(Auth::guard('admin')->check())
                       @include('admin.includes.message')
                         @elseif(Auth::guard('user')->check())
                          @include('includes.message')
                              @endif
                        <div class="col-md-12">
                            <div class="panel panel-default panel-fill">
                                <div class="panel-body">
                                    <form method="POST" action="{{ route('change.email') }}">
                                        @csrf

                                        <!-- Single Email Field -->
                                        <div class="col-md-12 col-sm-12 mb_01">
                                            <label>Email <strong>*</strong></label>
                                              <input type="email" name="email" class="form-control"
                                               value="{{ old('email', Auth::guard('admin')->check() ? Auth::guard('admin')->user()->email : (Auth::guard('user')->check() ? Auth::guard('user')->user()->email : '')) }}">
                                            @error('email')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>

                                        <div class="clearfix"></div>

                                        <div class="col-lg-12 text-center">
                                            <div class="qroo11">
                                                <button type="submit" class="btn btn-primary waves-effect waves-light w-md qroo">Save Changes</button>
                                            </div>
                                        </div>

                                    </form>
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
