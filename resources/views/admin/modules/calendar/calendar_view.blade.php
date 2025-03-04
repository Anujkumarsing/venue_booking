@extends('admin.layout.app')
@section('title', 'Admin | Calendar')
@section('links')
    @include('admin.includes.links')
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
                        <h4 class="pull-left page-title mob_ad1">Calendar</h4>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="panel panel-default">
                            <div class="panel-heading rm02 rm04 rm_new01">
                                <form id="calendarFilterForm" role="form" action="{{ route('calendar_view') }}"
                                    method="post">
                                    @csrf
                                    <div class="form-group">
                                        <label for="">Calendar</label>
                                        <select class="form-control rm06" name="calendar_id" id="calendarDropdown">
                                            <option value=""
                                                {{ empty(session('selected_calendar_id')) ? 'selected' : '' }}>Select
                                                calendar</option>
                                            @foreach ($calendars as $calendar)
                                                <option value="{{ $calendar->id }}"
                                                    {{ session('selected_calendar_id') == $calendar->id ? 'selected' : '' }}>
                                                    {{ $calendar->calendar_name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="rm05">
                                        {{-- <button class="btn btn-primary waves-effect waves-light w-md"
                                            type="submit">Search</button> --}}
                                        <a href="{{ route('calendar.reset') }}"
                                            class="btn btn-secondary waves-effect waves-light w-md">Reset</a>
                                    </div>
                                </form>
                            </div>
                        </div>
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
        @include('admin.includes.footer')
    </div>
@endsection
@section('scripts')
    @include('admin.includes.scripts')
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.js"></script>
    {{-- <script>
       $(document).ready(function() {
    var calendarsContainer = $("#container");
    calendarsContainer.html("");

    function fetchBookings(year, month, calendarId, successCallback) {
        let calendarFilter = calendarId || "{{ session('selected_calendar_id') }}";

        $.ajax({
            url: "{{ route('calendar.fetchBookings') }}",
            method: "GET",
            data: { year: year, month: month, calendar_id: calendarFilter },
            success: function(response) {
                if (!response.length) return;

                var events = response.map((booking) => ({
                    id: booking.id,
                    title: booking.title || "Booking",
                    start: booking.booking_dt.split(" ")[0],
                    classNames: ["custom-event", booking.color],
                    url: "{{ route('calendar.view.booking', '') }}/" + booking.id
                }));

                successCallback(events);
            },
            error: function(xhr) {
                console.error("AJAX Error:", xhr.responseText);
            }
        });
    }

    function loadFullYearCalendar(year, calendarId = "") {
        calendarsContainer.html("");
        for (let month = 1; month <= 12; month++) {
            let monthContainer = $(`<div class="calendar-container" id="calendar-${month}"></div>`);
            calendarsContainer.append(monthContainer);

            let calendar = new FullCalendar.Calendar(monthContainer[0], {
                initialView: "dayGridMonth",
                initialDate: `${year}-${month.toString().padStart(2, "0")}-01`,
                events: function(fetchInfo, successCallback, failureCallback) {
                    fetchBookings(year, month, calendarId, successCallback);
                },
                eventContent: function(arg) {
                    return { html: `<div class="custom-event ${arg.event.classNames.join(" ")}">${arg.event.title}</div>` };
                },
            });

            calendar.render();
        }
    }

    function loadSingleMonthCalendar(year, month, calendarId) {
        calendarsContainer.html("");

        let monthContainer = $(`<div class="calendar-container" id="calendar-${month}"></div>`);
        calendarsContainer.append(monthContainer);

        let calendar = new FullCalendar.Calendar(monthContainer[0], {
            initialView: "dayGridMonth",
            initialDate: `${year}-${month.toString().padStart(2, "0")}-01`,
            events: function(fetchInfo, successCallback, failureCallback) {
                fetchBookings(year, month, calendarId, successCallback);
            },
            eventContent: function(arg) {
                return { html: `<div class="custom-event ${arg.event.classNames.join(" ")}">${arg.event.title}</div>` };
            },
        });

        calendar.render();
    }

    $("#calendarDropdown").change(function() {
    let selectedCalendarId = $(this).val();
    let yearDropdown = $("select[name='year']");
    let monthDropdown = $("select[name='month']");

    if (selectedCalendarId) {
        $.ajax({
            url: "{{ route('calendar.fetchBookings') }}",
            method: "GET",
            data: { calendar_id: selectedCalendarId },
            success: function(response) {
                if (!response.length) return;

                let availableYears = [...new Set(response.map(b => new Date(b.booking_dt).getFullYear()))];

                yearDropdown.html('<option value="">Select Year</option>');
                availableYears.forEach(year => {
                    yearDropdown.append(`<option value="${year}">${year}</option>`);
                });

                // Default select current year if available
                let currentYear = new Date().getFullYear();
                if (availableYears.includes(currentYear)) {
                    yearDropdown.val(currentYear).trigger("change");
                } else {
                    yearDropdown.val("").trigger("change");
                }
            },
            error: function(xhr) {
                console.error("Error fetching bookings:", xhr.responseText);
            }
        });
    } else {
        window.location.href = "{{ route('calendar_view') }}";
    }
});

$("select[name='year']").change(function() {
    let selectedYear = $(this).val();
    let selectedCalendar = $("#calendarDropdown").val();
    let monthDropdown = $("select[name='month']");

    if (selectedYear) {
        $.ajax({
            url: "{{ route('calendar.fetchBookings') }}",
            method: "GET",
            data: { year: selectedYear, calendar_id: selectedCalendar },
            success: function(response) {
                monthDropdown.html('<option value="">Select Month</option>');

                let availableMonths = [...new Set(response.map(b => new Date(b.booking_dt).getMonth() + 1))];

                availableMonths.forEach(month => {
                    let monthName = new Date(2000, month - 1, 1).toLocaleString('default', { month: 'long' });
                    monthDropdown.append(`<option value="${month}">${monthName}</option>`);
                });

                // Default show full year calendar if months exist
                if (availableMonths.length) {
                    loadFullYearCalendar(selectedYear, selectedCalendar);
                } else {
                    calendarsContainer.html("<p>No bookings available for this year.</p>");
                }
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
    let selectedCalendarId = $("#calendarDropdown").val();

    if (selectedYear && selectedMonth) {
        loadSingleMonthCalendar(selectedYear, selectedMonth, selectedCalendarId);
    } else {
        $("select[name='year']").trigger("change");
    }
});


    $("#calendarFilterForm").submit(function(e) {
        e.preventDefault();
        let selectedCalendarId = $("#calendarDropdown").val();

        $.post("{{ route('calendar_view') }}", { _token: "{{ csrf_token() }}", calendar_id: selectedCalendarId })
            .done(function(response) {
                location.reload();
            })
            .fail(function(xhr) {
                console.error("POST Error:", xhr.responseText);
            });
    });

    let storedCalendarId = "{{ session('selected_calendar_id') }}";
    let currentYear = new Date().getFullYear();
    let monthDropdown = $("select[name='month']");

    function populateMonthDropdown() {
        monthDropdown.html('<option value="">Any Month</option>');
        for (let month = 1; month <= 12; month++) {
            let monthName = new Date(2000, month - 1, 1).toLocaleString('default', { month: 'long' });
            monthDropdown.append(`<option value="${month}">${monthName}</option>`);
        }
    }

    if (storedCalendarId) {
        $("#calendarDropdown").val(storedCalendarId).trigger("change");
    } else {
        $("select[name='year']").val(currentYear);
        populateMonthDropdown();
        loadFullYearCalendar(currentYear, "");
    }
});
    </script> --}}
    <script>
        $(document).ready(function() {
    var calendarsContainer = $("#container");
    calendarsContainer.html("");

    function fetchBookings(year, month, calendarId, successCallback) {
        let calendarFilter = calendarId || "{{ session('selected_calendar_id') }}";

        $.ajax({
            url: "{{ route('calendar.fetchBookings') }}",
            method: "GET",
            data: { year: year, month: month, calendar_id: calendarFilter },
            success: function(response) {
                if (!response.length) return;

                var events = response.map((booking) => ({
                    id: booking.id,
                    title: booking.title || "Booking",
                    start: booking.booking_dt.split(" ")[0],
                    classNames: ["custom-event", booking.color],
                    url: "{{ route('calendar.view.booking', '') }}/" + booking.id
                }));

                successCallback(events);
            },
            error: function(xhr) {
                console.error("AJAX Error:", xhr.responseText);
            }
        });
    }

    function loadFullYearCalendar(year, calendarId = "") {
        calendarsContainer.html("");
        for (let month = 1; month <= 12; month++) {
            let monthContainer = $(`<div class="calendar-container" id="calendar-${month}"></div>`);
            calendarsContainer.append(monthContainer);

            let calendar = new FullCalendar.Calendar(monthContainer[0], {
                initialView: "dayGridMonth",
                initialDate: `${year}-${month.toString().padStart(2, "0")}-01`,
                events: function(fetchInfo, successCallback, failureCallback) {
                    fetchBookings(year, month, calendarId, successCallback);
                },
                eventContent: function(arg) {
                    return { html: `<div class="custom-event ${arg.event.classNames.join(" ")}">${arg.event.title}</div>` };
                },
            });

            calendar.render();
        }
    }

    function loadSingleMonthCalendar(year, month, calendarId) {
        calendarsContainer.html("");

        let monthContainer = $(`<div class="calendar-container" id="calendar-${month}"></div>`);
        calendarsContainer.append(monthContainer);

        let calendar = new FullCalendar.Calendar(monthContainer[0], {
            initialView: "dayGridMonth",
            initialDate: `${year}-${month.toString().padStart(2, "0")}-01`,
            events: function(fetchInfo, successCallback, failureCallback) {
                fetchBookings(year, month, calendarId, successCallback);
            },
            eventContent: function(arg) {
                return { html: `<div class="custom-event ${arg.event.classNames.join(" ")}">${arg.event.title}</div>` };
            },
        });

        calendar.render();
    }

    $("#calendarDropdown").change(function() {
        let selectedCalendarId = $(this).val();
        let yearDropdown = $("select[name='year']");
        let monthDropdown = $("select[name='month']");

        if (selectedCalendarId) {
            $.ajax({
                url: "{{ route('calendar.fetchBookings') }}",
                method: "GET",
                data: { calendar_id: selectedCalendarId },
                success: function(response) {
                    if (!response.length) return;

                    let earliestYear = Math.min(...response.map(b => new Date(b.booking_dt).getFullYear()));

                    // ✅ Default "Any Month" select rahe
                    monthDropdown.val("");

                    yearDropdown.val(earliestYear).trigger("change");
                },
                error: function(xhr) {
                    console.error("Error fetching bookings:", xhr.responseText);
                }
            });
        } else {
            window.location.href = "{{ route('calendar_view') }}";
        }
    });

    $("select[name='year']").change(function() {
        let selectedYear = $(this).val();
        let selectedCalendar = $("#calendarDropdown").val();
        let monthDropdown = $("select[name='month']");

        if (selectedYear) {
            $.ajax({
                url: "{{ route('calendar.fetchBookings') }}",
                method: "GET",
                data: { year: selectedYear, calendar_id: selectedCalendar },
                success: function(response) {
                    monthDropdown.html('<option value="">Any Month</option>');

                    for (let month = 1; month <= 12; month++) {
                        let monthName = new Date(2000, month - 1, 1).toLocaleString('default', { month: 'long' });
                        monthDropdown.append(`<option value="${month}">${monthName}</option>`);
                    }

                    monthDropdown.val(""); // ✅ Default "Any Month"
                    loadFullYearCalendar(selectedYear, selectedCalendar);
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
        let selectedCalendarId = $("#calendarDropdown").val();

        if (selectedYear && selectedMonth) {
            loadSingleMonthCalendar(selectedYear, selectedMonth, selectedCalendarId);
        } else {
            loadFullYearCalendar(selectedYear, selectedCalendarId); 
        }
    });

    $("#calendarFilterForm").submit(function(e) {
        e.preventDefault();
        let selectedCalendarId = $("#calendarDropdown").val();

        $.post("{{ route('calendar_view') }}", { _token: "{{ csrf_token() }}", calendar_id: selectedCalendarId })
            .done(function(response) {
                location.reload();
            })
            .fail(function(xhr) {
                console.error("POST Error:", xhr.responseText);
            });
    });

    let storedCalendarId = "{{ session('selected_calendar_id') }}";
    let currentYear = new Date().getFullYear();
    let monthDropdown = $("select[name='month']");

    function populateMonthDropdown() {
        monthDropdown.html('<option value="">Any Month</option>');
        for (let month = 1; month <= 12; month++) {
            let monthName = new Date(2000, month - 1, 1).toLocaleString('default', { month: 'long' });
            monthDropdown.append(`<option value="${month}">${monthName}</option>`);
        }
    }

    if (storedCalendarId) {
        $("#calendarDropdown").val(storedCalendarId).trigger("change");
    } else {
        $("select[name='year']").val(currentYear);
        populateMonthDropdown();
        loadFullYearCalendar(currentYear, "");
    }
});
    </script>
@endsection
