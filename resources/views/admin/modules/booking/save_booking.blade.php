@extends('admin.layout.app')
@section('title', @$booking ? 'Admin | Edit Booking' : 'Admin | Add Booking')
@section('links')
    @include('admin.includes.links')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">

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
                    <h4 class="pull-left page-title">{{ @$booking ? 'Edit' : 'Add' }} Booking</h4>
                </div>
              </div>

          <!-- BODY AREA START -->
          <div class="row">
            @include('admin.includes.message')
                <div class="col-md-12">

          <div class="panel panel-default panel-fill">

          <div class="panel-body rm02 rm04">

          <div class="nst_dsgg">
            <form id="bookingForm" class="form-flex" method="post" action="{{ route('save.booking', @$booking->id) }}" enctype="multipart/form-data">
                @csrf
                <div class="col-md-6 col-sm-6 mb_01">
                    <label for="">Calendar <strong>*</strong></label>
                    <select class="form-control rm06" name="calendar_id" id="calendarDropdown">
                        <option value="" disabled selected>Select Calendar</option>
                        @foreach ($calendars as $calendar)
                            <option value="{{ $calendar->id }}"
                                {{ old('calendar_id', @$booking->calendar_id) == $calendar->id ? 'selected' : '' }}>
                                {{ $calendar->calendar_name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6 col-sm-6 mb_01">
                    <label for="">User <strong>*</strong></label>
                    <select class="form-control rm06" name="user_id" id="userDropdown">
                        <option value="" disabled selected>Select User</option>
                    </select>
                </div>

                <div class="col-md-6 col-sm-6 mb_01">
                    <label>Date <strong>*</strong></label>
                    <input type="text" id="datepicker" name="booking_dt" class="form-control calander_icn" placeholder="Select Date" value="{{ old('booking_dt', @$booking->booking_dt) }}">
                </div>

              <div class="col-md-6 col-sm-6 mb_01">
                  <label>Client 1 <strong>*</strong></label>
                  <input type="text" name="client_1" value="{{ old('client_1', @$booking->client_1) }}" class="form-control">
                </div>
              <div class="col-md-6 col-sm-6 mb_01">
                  <label>Client 2 <strong>(Optional)</strong></label>
                  <input type="text" name="client_2" value="{{ old('client_2', @$booking->client_2) }}" class="form-control">
                </div>
              <div class="col-md-6 col-sm-6 mb_01">
                  <label>Email <strong>*</strong></label>
                  <input type="email" name="email" value="{{ old('email', @$booking->email) }}" class="form-control">
                </div>
              <div class="col-md-6 col-sm-6 mb_01">
                  <label>Phone <strong>*</strong></label>
                  <input type="text" name="phone" value="{{ old('phone', @$booking->phone) }}" class="form-control">
                </div>
              <div class="col-md-6 col-sm-6 mb_01">
                  <label>Guest Count <strong>*</strong></label>
                  <input type="text" name="guest_count" value="{{ old('guest_count', @$booking->guest_count) }}" class="form-control">
                </div>
              <div class="col-md-12 mb_01">
                  <label for="AboutMe">Notes</label>
                  <textarea style="height: 125px" name="notes" class="form-control">{{ old('notes', @$booking->notes) }}</textarea>
              </div>

              <div class="w-100"></div>
              <div class="col-md-6 col-sm-6 mb_01">
                <label for="">Upload File</label>
                <div class="fileUpload btn btn-primary cust_file clearfix">
                    <span class="upld_txt"><i class="fa fa-upload upld-icon" aria-hidden="true"></i> Click here to Upload file</span>
                    <input type="file" name="file" class="upload" id="fileInput" accept=".jpg,.jpeg,.png,.pdf">
                </div>
                <div id="filePreview" style="margin-top: 10px;">
                    @if(!empty($booking->file))
                        @php
                            $filePath = asset('booking/' . $booking->file);
                            $fileExtension = pathinfo($booking->file, PATHINFO_EXTENSION);
                        @endphp

                        @if($fileExtension == 'pdf')
                            <iframe src="{{ $filePath }}" width="100%" height="300px"></iframe>
                        @else
                            <img src="{{ $filePath }}" alt="Preview" style="max-width: 100px; max-height: 100px;">
                        @endif
                    @endif
                </div>
            </div>
              <div class="clearfix"></div>
              <div class="col-lg-12 text-center">
                <div class="qroo11">
                    @if(!isset($booking))
                        <!-- Agar new booking hai to "Save" button dikhao -->
                        <button type="submit" class="btn btn-primary">Save</button>
                    @else
                        <!-- Agar edit mode hai -->
                        <button type="submit" name="action" value="approve_update" class="btn {{ in_array($booking->booking_status, [0, 3]) ? 'btn-primary' : 'btn-primary' }}">
                            {{ in_array($booking->booking_status, [0, 3]) ? 'Approve & Update' : 'Update' }}
                        </button>
                    @endif
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

          <!--FOOTER-->
          @include('admin.includes.footer')
      </div>
</div>
@endsection
@section('scripts')
    @include('admin.includes.scripts')
    <script>
        const calendars = @json($calendars);

        const preSelectedUserId = {{ old('user_id', @$booking->user_id) ?? 'null' }};

        document.getElementById('calendarDropdown').addEventListener('change', function () {
            const calendarId = this.value;

            const selectedCalendar = calendars.find(calendar => calendar.id == calendarId);
            const userDropdown = document.getElementById('userDropdown');

            // Clear the user dropdown
            userDropdown.innerHTML = '<option value="" disabled selected>Select User</option>';

            if (selectedCalendar && selectedCalendar.users) {
                selectedCalendar.users.forEach(user => {
                    const option = document.createElement('option');
                    option.value = user.id;
                    option.textContent = user.fname + ' ' + user.lname;
                    if (user.id == preSelectedUserId) {
                        option.selected = true;
                    }

                    userDropdown.appendChild(option);
                });
            }
        });
        window.onload = function () {
            const calendarDropdown = document.getElementById('calendarDropdown');
            if (calendarDropdown.value) {
                calendarDropdown.dispatchEvent(new Event('change'));
            }
        };
    </script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script>
        flatpickr("#datepicker", {
            dateFormat: "d-m-Y",  // Set format to MM-DD-YYYY
            allowInput: true,     // Allow manual input
            minDate: "today"      // Prevent past date selection
        });
    </script>
  <script>
    document.getElementById('fileInput').addEventListener('change', function(event) {
        const file = event.target.files[0];
        const preview = document.getElementById('filePreview');

        preview.innerHTML = '';

        if (file) {
            const allowedExtensions = ['image/jpeg', 'image/png', 'image/jpg', 'application/pdf'];

            if (!allowedExtensions.includes(file.type)) {
                alert('Please upload a valid image (JPEG, PNG, JPG) or PDF file.');
                return;
            }

            if (file.size > 10 * 1024 * 1024) {
                alert('File size should not exceed 10 MB.');
                return;
            }

            const reader = new FileReader();

            reader.onload = function(e) {
                if (file.type === 'application/pdf') {
                    // ✅ Only show PDF inside an iframe, removed "Open PDF in New Tab" button
                    const iframe = document.createElement('iframe');
                    iframe.src = e.target.result;
                    iframe.width = '100%';
                    iframe.height = '300px';
                    preview.appendChild(iframe);
                } else {
                    const img = document.createElement('img');
                    img.src = e.target.result;
                    img.style.maxWidth = '100px';
                    img.style.maxHeight = '100px';
                    preview.appendChild(img);
                }
            };

            reader.readAsDataURL(file);
        }
    });
</script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.5/jquery.validate.min.js"></script>
    <script src="your-custom-script.js"></script>
    <script>
      $(document).ready(function() {
    jQuery.validator.addMethod("validate_email", function(value, element) {
        return /^[A-Z0-9._%+-]+@([A-Z0-9-]+\.)+[A-Z]{2,5}$/i.test(value);
    }, "Oops...! Check your email again");

    $('#bookingForm').validate({
        rules: {
            calendar_id: {
                required: true
            },
            user_id: {
                required: true
            },
            booking_dt: {
                required: true
            },
            client_1: {
                required: true
            },
            email: {
                required: true,
                email: true,
                validate_email: true
            },
            phone: {
                required: true,
                digits: true,
                minlength: 10,
                maxlength: 15
            },
            guest_count: {
                required: true,
                digits: true,
                minlength: 1,
                maxlength: 4
            },
            notes: {
                required: false
            },
            file: {
                required: false
            }
        },
        messages: {
            calendar_id: {
                required: "Please select a calendar."
            },
            user_id: {
                required: "Please select a user."
            },
            booking_dt: {
                required: "Date is required."
            },
            client_1: {
                required: "Client 1 is required."
            },
            email: {
                required: "Please enter an email address.",
                email: "Please enter a valid email address.",
                validate_email: "Oops...! Check your email again"
            },
            phone: {
                required: "Please enter a phone number.",
                digits: "Phone number must be digits only.",
                minlength: "Phone number must be at least 10 digits.",
                maxlength: "Phone number must not exceed 15 digits."
            },
            guest_count: {
                required: "Please enter the guest count.",
                digits: "Guest count must be a number.",
                minlength: "Guest count must have at least 1 digit.",
                maxlength: "Guest count cannot be more than 4 digits."
            },
            notes: {
                required: "Please enter notes."
            },
            file: {
                required: "Please upload a file."
            }
        },
        onkeyup: function(element) {
            $(element).valid();
        },
        onblur: function(element) {
            $(element).valid();
        },
        errorPlacement: function(error, element) {
            error.insertAfter(element);
        },
        submitHandler: function(form) {
            form.submit();
        }
    });

    $('#guest_count').on('input', function() {
        let value = $(this).val();
        if (value.length > 4) {
            $(this).val(value.slice(0, 4));
        }
    });
});
    </script>
@endsection
