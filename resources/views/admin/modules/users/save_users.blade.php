@extends('admin.layout.app')

@section('title', @$user ? 'Admin | Edit User' : 'Admin | Add User')

@section('links')
    @include('admin.includes.links')
    <style>
        .error {
            color: red;
            font-size: 14px;
            margin-top: 5px;
        }
        .is-invalid {
            border-color: red;
        }
    </style>
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
                        <h4 class="pull-left page-title">
                            {{ @$user ? 'Edit' : 'Add' }} User
                        </h4>
                        <a href="{{ route('manage.users') }}" class="rm_new04">
                            <i class="fa fa-chevron-left" aria-hidden="true"></i> Back
                        </a>
                    </div>
                </div>

                <!-- BODY AREA START -->
                <div class="row">
                    <div class="col-md-12">
                        <div class="panel panel-default panel-fill">
                            <div class="panel-body rm02 rm04">

                                <form method="post" class="form-flex" action="{{ route('save.users', @$user->id) }}" id="user_form">
                                    @csrf
                                    <div class="col-md-12 col-sm-6 mb_01">
                                        <label for="calendar">Select Calendars <strong>*</strong></label>
                                        <select name="calendar_ids[]" class="form-control rm06">
                                            <option value="" disabled selected>Select Calendar</option>
                                            @foreach($calendars as $calendar)
                                                <option value="{{ $calendar->id }}"
                                                    {{ in_array($calendar->id, old('calendar_ids', @$user && @$user->calendars ? @$user->calendars->pluck('id')->toArray() : [])) ? 'selected' : '' }}>
                                                    {{ $calendar->calendar_name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('calendar_ids')
                                            <div class="error">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6 col-sm-6 mb_01">
                                        <label>First Name <strong>*</strong></label>
                                        <input type="text" name="fname" value="{{ old('fname', @$user->fname) }}" class="form-control">
                                        @error('fname')
                                            <div class="error">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6 col-sm-6 mb_01">
                                        <label>Last Name <strong>*</strong></label>
                                        <input type="text" name="lname" value="{{ old('lname', @$user->lname) }}" class="form-control">
                                        @error('lname')
                                            <div class="error">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6 col-sm-6 mb_01">
                                        <label>Email <strong>*</strong></label>
                                        <input type="email" name="email" value="{{ old('email', @$user->email) }}" class="form-control">
                                        @error('email')
                                            <div class="error">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6 col-sm-6 mb_01">
                                        <label>Password <strong>{{ @$user ? '' : '*' }}</strong></label>
                                        <input type="password" name="password" class="form-control">
                                        @error('password')
                                            <div class="error">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="clearfix"></div>
                                    <div class="col-lg-12 text-center">
                                        <button class="btn btn-primary waves-effect waves-light w-md qroo" type="submit">
                                            {{ @$user ? 'Update' : 'Save' }}
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

@include('admin.includes.footer')

@endsection

@section('scripts')
    @include('admin.includes.scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.5/jquery.validate.min.js"></script>
    <script>
        $(document).ready(function () {
          // Custom method for letters only
          $.validator.addMethod("lettersOnly", function (value, element) {
              return this.optional(element) || /^[a-zA-Z\s]+$/.test(value);
          }, "This field must contain only letters.");

          // Custom method to check if at least one calendar is selected
        //   $.validator.addMethod("validCalendarSelection", function (value, element) {
        //       return $("select[name='calendar_ids[]'] option:selected").length > 0;
        //   }, "Please select at least one calendar.");

          // Form validation
          $("#user_form").validate({
              rules: {
                  fname: {
                      required: true,
                      maxlength: 255,
                      lettersOnly: true,
                  },
                  lname: {
                      required: true,
                      maxlength: 255,
                      lettersOnly: true,
                  },
                  email: {
                      required: true,
                      email: true,
                      remote: {
                          url: "{{ route('check.email') }}",
                          type: "POST",
                          data: {
                              _token: "{{ csrf_token() }}",
                              email: function () {
                                  return $("input[name='email']").val();
                              },
                              user_id: "{{ @$user ? $user->id : null }}", 
                          },
                      },
                  },
                  password: {
                      minlength: 6,
                      required: "{{ @$user ? false : true }}", // Password required only if not editing
                  },
                  'calendar_ids[]': {
                      required: true, // Custom rule
                  },
              },
              messages: {
                  fname: {
                      required: "First Name is required.",
                      maxlength: "First Name cannot exceed 255 characters.",
                      lettersOnly: "First Name must contain only letters.",
                  },
                  lname: {
                      required: "Last Name is required.",
                      maxlength: "Last Name cannot exceed 255 characters.",
                      lettersOnly: "Last Name must contain only letters.",
                  },
                  email: {
                      required: "Email is required.",
                      email: "Please enter a valid email address.",
                      remote: "This email is already in use.",
                  },
                  password: {
                      minlength: "Password must be at least 6 characters.",
                      required: "Password is required.",
                  },
                  'calendar_ids[]': {
                      required: "Please select at least one calendar.",
                  },
              },
              errorElement: "div",
              errorClass: "error",
              highlight: function (element) {
                  $(element).addClass("is-invalid");
              },
              unhighlight: function (element) {
                  $(element).removeClass("is-invalid");
              },
          });
      });
      </script>
@endsection

