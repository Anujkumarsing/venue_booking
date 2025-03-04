@extends('layout.app')
@section('title', 'User | Calendar')
@section('links')
    @include('includes.links')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.css">
    <style>
        #container .fc-daygrid-day {
            border: 1px solid #ccc;
            background-color: #f8f8f8;
            text-align: center;
            height: 80px;
            vertical-align: middle;
            padding: 5px;
        }

        .fc-event {
            font-size: 14px;
            font-weight: bold;
            padding: 5px;
            border-radius: 5px;
            color: white !important;
            text-align: center;
            width: 100%;
            display: block;
        }

        .custom-event {
            font-weight: bold;
            padding: 5px;
            border-radius: 5px;
            text-align: center;
            display: inline-block;
            width: 100%;
            color: white !important;
        }

        .custom-event.green {
            background-color: green;
        }

        .custom-event.red {
            background-color: red;
        }

        .custom-event.purple {
            background-color: purple;
        }

        .custom-event.gray {
            background-color: gray;
        }
    </style>
@endsection
@section('content')
@section('header')
    @include('includes.header')
@endsection
@section('leftsidemenubar')
    @include('includes.leftsidemenubar')
@endsection
<div id="wrapper">
    <div class="content-page">
        <div class="content">
            <div class="container">
                <div class="row">
                    <div class="col-sm-12">
                        <h4 class="pull-left page-title mob_ad1">Calendar</h4>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <!--Table area start-->
                        <div class="panel panel-default">
                            <div class="panel-body">
                                <div class="row">
                                    <div class="col-md-12 col-sm-12 col-xs-12">
                                        <div class="rm02 rm_new01 full_wwa">
                                            <form role="form">
                                                <div class="form-group">
                                                    <label for="">Year</label>
                                                    <select class="form-control rm06" name="year">
                                                        <option value="">Select year</option>
                                                        @foreach ($years as $year)
                                                            <option value="{{ $year }}"
                                                                {{ request('year') == $year ? 'selected' : '' }}>
                                                                {{ $year }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="form-group">
                                                    <label for="">Month</label>
                                                    <select class="form-control rm06" name="month">
                                                        <option value="">Any Month</option>
                                                        @foreach ($months as $month)
                                                            <option value="{{ $month }}"
                                                                {{ request('month') == $month ? 'selected' : '' }}>
                                                                {{ DateTime::createFromFormat('!m', $month)->format('F') }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="rm05">
                                                    <a href="{{ route('user.calendar.view') }}"
                                                        class="btn btn-secondary waves-effect waves-light w-md">Reset</a>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                    <div class="col-md-12 col-sm-12 col-xs-12">
                                        <div class="open_calendar">
                                            <form action="javascript:void(0)" method="post">
                                                <input id="datepicker-example13" type="text">
                                                <div id="container" style="margin: 10px 0 0 0"></div>
                                            </form>
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
        @include('includes.footer')
    </div>
@endsection
@section('scripts')
    @include('includes.scripts')
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.js"></script>
<script>
    $(document).ready(function() {
        var calendarsContainer = $("#container");
        calendarsContainer.html("");

        function fetchBookings(year, month, successCallback) {
            $.ajax({
                url: "{{ route('user.calendar.fetchBookings') }}",
                method: "GET",
                data: { year: year, month: month },
                success: function(response) {
                    if (!response.length) return;

                    var events = response.map((booking) => ({
                        id: booking.id,
                        title: booking.title || "Booking",
                        start: booking.booking_dt.split(" ")[0],
                        classNames: ["custom-event", booking.color],
                        url: "{{ route('user.calendar.view.booking', '') }}/" + booking.id
                    }));

                    successCallback(events);
                },
                error: function(xhr) {
                    console.error("AJAX Error:", xhr.responseText);
                }
            });
        }

        function loadFullYearCalendar(year) {
            calendarsContainer.html("");
            for (let month = 1; month <= 12; month++) {
                let monthContainer = $(`<div class="calendar-container" id="calendar-${month}"></div>`);
                calendarsContainer.append(monthContainer);

                let calendar = new FullCalendar.Calendar(monthContainer[0], {
                    initialView: "dayGridMonth",
                    initialDate: `${year}-${month.toString().padStart(2, "0")}-01`,
                    events: function(fetchInfo, successCallback, failureCallback) {
                        fetchBookings(year, month, successCallback);
                    },
                    eventContent: function(arg) {
                        return { html: `<div class="custom-event ${arg.event.classNames.join(" ")}">${arg.event.title}</div>` };
                    },
                });

                calendar.render();
            }
        }

        function loadSingleMonthCalendar(year, month) {
            calendarsContainer.html("");

            let monthContainer = $(`<div class="calendar-container" id="calendar-${month}"></div>`);
            calendarsContainer.append(monthContainer);

            let calendar = new FullCalendar.Calendar(monthContainer[0], {
                initialView: "dayGridMonth",
                initialDate: `${year}-${month.toString().padStart(2, "0")}-01`,
                events: function(fetchInfo, successCallback, failureCallback) {
                    fetchBookings(year, month, successCallback);
                },
                eventContent: function(arg) {
                    return { html: `<div class="custom-event ${arg.event.classNames.join(" ")}">${arg.event.title}</div>` };
                },
            });

            calendar.render();
        }

        $("select[name='year']").change(function() {
            let selectedYear = $(this).val();
            let monthDropdown = $("select[name='month']");

            if (selectedYear) {
                $.ajax({
                    url: "{{ route('user.calendar.fetchBookings') }}",
                    method: "GET",
                    data: { year: selectedYear },
                    success: function(response) {
                        monthDropdown.html('<option value="">Any Month</option>');

                        for (let month = 1; month <= 12; month++) {
                            let monthName = new Date(2000, month - 1, 1).toLocaleString('default', { month: 'long' });
                            monthDropdown.append(`<option value="${month}">${monthName}</option>`);
                        }

                        monthDropdown.val("");
                        loadFullYearCalendar(selectedYear);
                    },
                    error: function(xhr) {
                        console.error("Error fetching months:", xhr.responseText);
                    }
                });
            }
        });

        $("select[name='month']").change(function() {
            let selectedYear = $("select[name='year']").val();
            let selectedMonth = $(this).val();

            if (selectedYear && selectedMonth) {
                loadSingleMonthCalendar(selectedYear, selectedMonth);
            } else {
                loadFullYearCalendar(selectedYear);
            }
        });

        let currentYear = new Date().getFullYear();
        let monthDropdown = $("select[name='month']");

        function populateMonthDropdown() {
            monthDropdown.html('<option value="">Any Month</option>');
            for (let month = 1; month <= 12; month++) {
                let monthName = new Date(2000, month - 1, 1).toLocaleString('default', { month: 'long' });
                monthDropdown.append(`<option value="${month}">${monthName}</option>`);
            }
        }

        $("select[name='year']").val(currentYear);
        populateMonthDropdown();
        loadFullYearCalendar(currentYear);
    });
</script>
@endsection
