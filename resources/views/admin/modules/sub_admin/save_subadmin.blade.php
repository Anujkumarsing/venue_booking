@extends('admin.layout.app')
@section('title', @$subadmin  ? 'Admin | Edit Subadmin' : 'Admin | Add Subadmin')
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
                            {{ @$subadmin ? 'Edit' : 'Add' }} Sub Admin
                        </h4>

                        <!--<a href="add_students.html" class="rm_new04"><i class="fa fa-plus-circle" aria-hidden="true"></i> Add Students</a>-->
                        <a href="{{ route('manage.sub.admin') }}" class="rm_new04"><i class="fa fa-chevron-left arsg"
                                aria-hidden="true"></i> Back</a>
                    </div>
                </div>

                <!-- BODY AREA START -->
                <div class="row">
                    <div class="col-md-12">

                        <div class="panel panel-default panel-fill">

                            <div class="panel-body rm02 rm04">

                                    <form method="post" action="{{ route('save.sub.admin', @$subadmin->id) }}" enctype="multipart/form-data" id="subadmin_form">
                                        @csrf

                                <div class="col-md-6">
                                    <label>First Name <strong>*</strong></label>
                                    <input type="text" name="fname" value="{{ old('fname', @$subadmin->fname) }}" class="form-control required">
                                </div>
                                <div class="col-md-6">
                                    <label>Last Name <strong>*</strong></label>
                                    <input type="text" name="lname" value="{{ old('lname', @$subadmin->lname) }}" class="form-control required">
                                </div>
                                <div class="col-md-6">
                                    <label>Email <strong>*</strong></label>
                                    <input type="email" name="email" value="{{ old('email', @$subadmin->email) }}" class="form-control required">
                                </div>
                                <div class="col-md-6">
                                    <label>Password @if(!@$subadmin) <strong>*</strong> @endif</label>
                                    <input type="password" name="password" class="form-control {{ @$subadmin ? '' : 'required' }}">
                                </div>
                                <div class="col-lg-12 rm_new12">
                                    <p>Calendar <strong>*</strong></p>
                                    <div class="checkbox checkbox-primary position-relative check-errpos">
                                        <input id="calendar_1" type="checkbox" name="calendar_ids[]" value="1"
                                            @if(in_array(1, old('calendar_ids', isset($subadmin) ? @$subadmin->calendars->pluck('id')->toArray() : [])))
                                                checked
                                            @endif>
                                        <label for="calendar_1">All Calendar</label>
                                    </div>
                                    @foreach(@$calendars as $calendar)
                                        @if($calendar->id != 1)
                                            <div class="checkbox checkbox-primary">
                                                <input id="calendar_{{ $calendar->id }}" type="checkbox" name="calendar_ids[]"
                                                    value="{{ $calendar->id }}"
                                                    @if(in_array($calendar->id, old('calendar_ids', isset($subadmin) ? $subadmin->calendars->pluck('id')->toArray() : [])))
                                                        checked
                                                    @endif>
                                                <label for="calendar_{{ $calendar->id }}">{{ $calendar->calendar_name }}</label>
                                            </div>
                                        @endif
                                    @endforeach
                                </div>
                                <div class="clearfix"></div>
                                <div class="col-lg-12 text-center">
                                    <div class="qroo11">
                                        <button class="btn btn-primary waves-effect waves-light" type="submit">
                                            {{ @$subadmin ? 'Update' : 'Save' }}
                                        </button>
                                    </div>
                                </div>

                                </form>
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
@include('admin.includes.scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.5/jquery.validate.min.js"></script>
<script>
    $(document).ready(function () {
        const allCalendarCheckbox = $('#calendar_1');
        allCalendarCheckbox.change(function () {
            if ($(this).is(':checked')) {
                $('input[name="calendar_ids[]"]').not(this).prop('checked', false).prop('disabled', true);
            } else {
                $('input[name="calendar_ids[]"]').not(this).prop('disabled', false);
            }
        });
    });
</script>
<script>
    $(document).ready(function () {
    $.validator.addMethod("lettersOnly", function(value, element) {
        return this.optional(element) || /^[A-Za-z]+$/.test(value);
    }, "Only letters are allowed.");

    $('#subadmin_form').validate({
        rules: {
            fname: {
                required: true,
                minlength: 2,
                lettersOnly: true
            },
            lname: {
                required: true,
                minlength: 2,
                lettersOnly: true
            },
            email: {
                required: true,
                email: true,
                remote: {
                    url: "{{ route('check.email.unique') }}",
                    type: "POST",
                    data: {
                        _token: "{{ csrf_token() }}",
                        email: function () {
                            return $("input[name='email']").val();
                        },
                        id: "{{ @$subadmin->id }}"
                    }
                }
            },
            password: {
                required: function () {
                    return "{{ @$subadmin }}" ? false : true;
                },
                minlength: 6
            },
            'calendar_ids[]': {
                required: true,
                minlength: 1
            }
        },
        messages: {
            fname: {
                required: "First Name is required.",
                minlength: "First Name must be at least 2 characters.",
                lettersOnly: "First Name must contain only letters."
            },
            lname: {
                required: "Last Name is required.",
                minlength: "Last Name must be at least 2 characters.",
                lettersOnly: "Last Name must contain only letters."
            },
            email: {
                required: "Email is required.",
                email: "Enter a valid email address.",
                remote: "Email already in use. Please use a different email."
            },
            password: {
                required: "Password is required.",
                minlength: "Password must be at least 6 characters."
            },
            'calendar_ids[]': {
                required: "Select at least one calendar.",
                minlength: "Select at least one calendar."
            }
        },
        errorElement: "div",
        errorClass: "error",
        highlight: function (element) {
            $(element).addClass("is-invalid");
        },
        unhighlight: function (element) {
            $(element).removeClass("is-invalid");
        },
        errorPlacement: function (error, element) {
            if (element.attr("name") === "calendar_ids[]") {
                error.appendTo(".check-errpos");
            } else {
                error.insertAfter(element);
            }
        }
    });
});

</script>
@endsection
