@extends('admin.layout.app')
@section('title', @$calendar ? 'Admin | Edit Calendar' : 'Admin | Add Calendar')
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
            <div class="row">
                <div class="col-sm-12">
                    <h4 class="pull-left page-title">
                        {{ @$calendar ? 'Edit' : 'Add' }} Calendar
                    </h4>
                    <a href="{{ route('manage.calendar') }}" class="rm_new04">
                        <i class="fa fa-chevron-left" aria-hidden="true"></i> Back
                    </a>
                </div>
            </div>
            <div class="row">
                @include('admin.includes.message')
                <div class="col-md-12">
                    <div class="panel panel-default panel-fill">
                        <div class="panel-heading title_style">
                            <h3 class="panel-title">
                                {{ @$calendar ? 'Edit' : 'Add' }} Calendar
                            </h3>
                        </div>
                        <div class="panel-body">
                            <form method="post" action="" enctype="multipart/form-data" id="calendar_form">
                                @csrf
                                <div class="col-md-6">
                                    <label>Calendar Name <strong>*</strong></label>
                                    <input type="text" name="calendar_name" value="{{ @$calendar->calendar_name }}" class="form-control required">
                                </div>
                                <div class="col-md-6">
                                    <label>Email <strong>*</strong></label>
                                    <input type="email" name="email" value="{{ @$calendar->email }}" class="form-control required">
                                </div>
                                <div class="clearfix"></div>
                                <div class="col-lg-12 text-center">
                                    <div class="qroo11">
                                    <button class="btn btn-primary waves-effect waves-light w-md qroo" type="submit">
                                        {{ @$calendar ? 'Update' : 'Save' }}
                                    </button>
                                </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @include('admin.includes.footer')
</div>
@endsection
@section('scripts')
    @include('admin.includes.scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.5/jquery.validate.min.js"></script>
    <script>
$(document).ready(function() {
    // ✅ Step 1: Custom Email Validation Method
    jQuery.validator.addMethod("validate_email", function(value, element) {
        return /^[A-Z0-9._%+-]+@([A-Z0-9-]+\.)+[A-Z]{2,5}$/i.test(value);
    }, "Oops...! Check your email again");

    // ✅ Step 2: Form Validation
    $('#calendar_form').validate({
        rules: {
            calendar_name: {
                required: true,
                minlength: 3
            },
            email: {
                required: true,
                email: true,
                validate_email: true // ✅ Custom validation method added
            }
        },
        messages: {
            calendar_name: {
                required: "Please enter a calendar name",
                minlength: "Calendar name must be at least 3 characters long"
            },
            email: {
                required: "Please enter an email",
                email: "Please enter a valid email address",
                validate_email: "Oops...! Check your email again"
            }
        },
        errorElement: 'span',
        errorClass: 'error',
        errorPlacement: function (error, element) {
            error.insertAfter(element);
        },
        highlight: function (element, errorClass, validClass) {
            $(element).addClass('is-invalid');
        },
        unhighlight: function (element, errorClass, validClass) {
            $(element).removeClass('is-invalid');
        }
    });
});
    </script>
@endsection
