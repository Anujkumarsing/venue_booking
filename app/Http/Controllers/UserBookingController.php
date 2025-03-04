<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Booking;
use App\Models\User;
use App\Models\Calendar;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class UserBookingController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::guard('user')->user();

        // Fetch calendars associated with the logged-in user
        $calendarIds = $user->calendars()->pluck('calendars.id');

        // Get bookings where the user_id matches the logged-in user and calendar_id is in the allowed list
        $bookings = Booking::whereIn('calendar_id', $calendarIds)
            ->where('user_id', $user->id) // Ensure user sees only their own bookings
            ->with(['calendar', 'user']);

        // Filter by booking status
        if ($request->filled('status')) {
            if ($request->status == 'all_awaiting') {
                $bookings->whereIn('booking_status', [0, 3]);
            } else {
                $bookings->where('booking_status', $request->status);
            }
        }

        // Filter by date range
        if ($request->filled('from_date') || $request->filled('to_date')) {
            $fromDate = $request->input('from_date') ? date('Y-m-d 00:00:00', strtotime($request->from_date)) : null;
            $toDate = $request->input('to_date') ? date('Y-m-d 23:59:59', strtotime($request->to_date)) : null;

            if ($fromDate && !$toDate) {
                $bookings->where('booking_dt', '>=', $fromDate);
            } elseif ($toDate && !$fromDate) {
                $bookings->where('booking_dt', '<=', $toDate);
            } elseif ($fromDate && $toDate) {
                $bookings->whereBetween('booking_dt', [$fromDate, $toDate]);
            }
        }

        // Order by latest booking and paginate
        $bookings = $bookings->orderBy('created_at', 'desc')->paginate(5);

        return view('booking.view_booking', compact('bookings'));
    }

    public function saveBooking(Request $request, $id = null)
{
    $data = [];

    if ($request->isMethod('post')) {
        $rules = [
            'calendar_id' => 'required',
            'client_1'    => 'required|string',
            'client_2'    => 'nullable|string',
            'email'       => 'required|email|max:255',
            'phone'       => 'required|string',
            'guest_count' => 'required',
            'notes'       => 'nullable|string',
            'file'        => 'nullable|file|mimes:jpg,jpeg,png,pdf',
            'booking_dt'  => 'required|date',
        ];

        $validated = $request->validate($rules);

        $existingBooking = Booking::where('calendar_id', $validated['calendar_id'])
            ->whereDate('booking_dt', $validated['booking_dt'])
            ->where('id', '!=', $id)
            ->exists();

        if ($existingBooking) {
            return redirect()->back()->withErrors(['booking_dt' => 'A booking for this calendar on this date already exists.']);
        }
        if ($id) {
            $existingBooking = Booking::find($id);
            if ($existingBooking) {
                $oldDate = Carbon::parse($existingBooking->booking_dt)->format('Y-m-d');
                $newDate = Carbon::parse($request->booking_dt)->format('Y-m-d');

                if ($newDate !== $oldDate) {
                    return redirect()->back()->withErrors(['booking_dt' => 'You cannot edit the booking date.']);
                }
            }
        }
        $fileName = null;
        if ($request->hasFile('file')) {
            if (!file_exists(public_path('booking'))) {
                mkdir(public_path('booking'), 0777, true);
            }
            $fileName = uniqid(true) . '.' . $request->file('file')->getClientOriginalExtension();
            $request->file('file')->move(public_path('booking'), $fileName);
        } else {
            if ($id) {
                $existingBooking = Booking::find($id);
                $fileName = $existingBooking->file ?? null;
            }
        }
        $bookingData = [
            'user_id'       => Auth::guard('user')->id(),
            'calendar_id'   => $validated['calendar_id'],
            'client_1'      => $validated['client_1'],
            'client_2'      => $validated['client_2'],
            'email'         => $validated['email'],
            'phone'         => $validated['phone'],
            'guest_count'   => $validated['guest_count'],
            'notes'         => $validated['notes'],
            'file'          => $fileName,
            'booking_dt'    => $validated['booking_dt'],
            'booking_status'=> $id ? 3 : 0,
        ];

        if ($id) {
            Booking::where('id', $id)->update($bookingData);
            return redirect()->back()->with('success', 'Booking updated successfully.');
        } else {
            Booking::create($bookingData);
            return redirect()->back()->with('success', 'Booking added successfully.');
        }
    }

    $data['calendars'] = Auth::guard('user')->user()->calendars;
    $data['booking'] = $id ? Booking::find($id) : null;
    return view('booking.save_booking', $data);
}

    public function show($id){
        $booking = Booking::with(['calendar', 'user'])->findOrFail($id);
        // dd($booking);
        return view('booking.view', compact('booking'));
    }
    public function userCalendarView(Request $request)
    {
        $user = Auth::guard('user')->user();

        // User can only see bookings related to their ID
        $bookings = Booking::where('user_id', $user->id);

        if ($request->filled('year')) {
            $bookings->whereYear('booking_dt', $request->year);
        }

        if ($request->filled('month')) {
            $bookings->whereMonth('booking_dt', $request->month);
        }

        $bookings = $bookings->get();
        $years = Booking::where('user_id', $user->id)
            ->selectRaw('YEAR(booking_dt) as year')
            ->distinct()
            ->orderByDesc('year')
            ->pluck('year');
        $months = collect();
        if ($request->filled('year')) {
            $months = Booking::where('user_id', $user->id)
                ->whereYear('booking_dt', $request->year)
                ->selectRaw('MONTH(booking_dt) as month')
                ->distinct()
                ->pluck('month');
        }

        return view('calendar.calendar_view', compact(
            'bookings', 'years', 'months'
        ));
    }

    public function fetchUserBookings(Request $request)
    {
        $user = Auth::guard('user')->user();

        $bookings = Booking::where('user_id', $user->id);

        if ($request->filled('year')) {
            $bookings->whereYear('booking_dt', $request->year);
        }

        if ($request->filled('month')) {
            $bookings->whereMonth('booking_dt', $request->month);
        }

        $bookingsData = $bookings->orderBy('booking_dt', 'asc')->get();

        return response()->json($bookingsData->map(function ($booking) {
            return [
                'id' => $booking->id,
                'title' => $booking->client_1 ?? 'Booking',
                'booking_dt' => $booking->booking_dt,
                'color' => match ($booking->booking_status) {
                    1 => 'green',
                    0, 3 => 'purple',
                    4 => 'red',
                    default => 'gray',
                },
            ];
        }));
    }
    public function calendar_viewBooking($id){
        $booking = Booking::with('calendar', 'user')->findOrFail($id);
        return view('calendar.calendar_booking', compact('booking'));
    }
}
