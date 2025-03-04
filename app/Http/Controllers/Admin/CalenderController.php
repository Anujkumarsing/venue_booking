<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Calendar;
use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CalenderController extends Controller
{
    public function index()
    {
        $user = Auth::guard('admin')->user();
        if (!$user || !in_array($user->user_type_id, [1, 2])) {
            abort(403, 'Unauthorized access');
        }
        if ($user->user_type_id == 1) {
            $calendars = Calendar::where('id', '!=', 1)
                ->orderBy('created_at', 'desc')
                ->paginate(20);
        } elseif ($user->user_type_id == 2) {
            $hasAllCalendar = DB::table('user_calendar')
                ->where('user_id', $user->id)
                ->where('calendar_id', 1)
                ->exists();

            if ($hasAllCalendar) {
                $calendars = Calendar::where('id', '!=', 1)
                    ->orderBy('created_at', 'desc')
                    ->paginate(20);
            } else {
                $calendars = $user->calendars()
                    ->orderBy('created_at', 'desc')
                    ->paginate(20);
            }
        }
        return view('admin.modules.calendar.manage_calendar', compact('calendars'));
    }

    public function saveCalendar(Request $request, $id = null)
    {
        $data = [];

        if ($request->all()) {
            if ($id) {
                $validated = $request->validate([
                    'calendar_name' => ['required', 'string', Rule::unique('calendars')->ignore($id)],
                    'email' => ['required', 'email', Rule::unique('calendars')->ignore($id)],
                ]);
            } else {
                $validated = $request->validate([
                    'calendar_name' => 'required|string|unique:calendars,calendar_name',
                    'email' => 'required|email|unique:calendars,email',
                ]);
            }

            $calendarData = ['status' => 1];

            if ($id) {
                Calendar::where('id', $id)->update($calendarData + $validated);
                return redirect()->route('manage.calendar')->with('success', 'Calendar updated successfully.');
            } else {
                Calendar::create($calendarData + $validated);
                return redirect()->route('manage.calendar')->with('success', 'Calendar added successfully.');
            }
        }

        if ($id) {
            $data['calendar'] = Calendar::find($id);
        }

        return view('admin.modules.calendar.save_calendar')->with($data);
    }
    public function toggleCalendarStatus($id)
    {
        $calendar = Calendar::find($id);

        if ($calendar) {
            $calendar->status = $calendar->status == 1 ? 0 : 1;
            $calendar->save();

            $message = $calendar->status == 1 ? 'Calendar activated successfully.' : 'Calendar deactivated successfully.';
            return redirect()->back()->with('success', $message);
        } else {
            return redirect()->back()->with('error', 'Calendar not found.');
        }
    }

    public function deleteCalendar($id)
    {
        $calendar = Calendar::find($id);

        if ($calendar) {
            $hasFutureBooking = Booking::where('calendar_id', $id)
                ->where('booking_dt', '>', now())
                ->exists();

            if ($hasFutureBooking) {
                return redirect()->back()->with('error', 'This calendar has future bookings and cannot be deleted.');
            }
            $calendar->delete();
            return redirect()->back()->with('success', 'Calendar deleted successfully.');
        } else {
            return redirect()->back()->with('error', 'Calendar not found.');
        }
    }
    public function calendar_view(Request $request)
    {
        $user = Auth::guard('admin')->user();
        if (!$user || !in_array($user->user_type_id, [1, 2])) {
            abort(403, 'Unauthorized access');
        }

        if ($user->user_type_id == 1) {
            $calendars = Calendar::whereIn('id', Booking::distinct()->pluck('calendar_id'))->get();
        }
        elseif ($user->user_type_id == 2) {
            $hasAllCalendar = DB::table('user_calendar')
                ->where('user_id', $user->id)
                ->where('calendar_id', 1)
                ->exists();

            if ($hasAllCalendar) {
                $calendars = Calendar::whereIn('id', Booking::distinct()->pluck('calendar_id'))->get();
            } else {
                $calendars = Calendar::whereIn('id', function ($query) use ($user) {
                    $query->select('calendar_id')
                        ->from('user_calendar')
                        ->where('user_id', $user->id);
                })->get();
            }
        }

        if ($request->isMethod('post') && $request->filled('calendar_id')) {
            session(['selected_calendar_id' => $request->calendar_id]);
        }
        $selectedCalendarId = session('selected_calendar_id', null);

        $bookings = Booking::query();

        if ($user->user_type_id == 2 && !$hasAllCalendar) {
            $bookings->whereIn('calendar_id', $calendars->pluck('id'));
        }

        if (!empty($selectedCalendarId)) {
            $bookings->where('calendar_id', $selectedCalendarId);
        }

        if ($request->filled('year')) {
            $bookings->whereYear('booking_dt', $request->year);
        }

        if ($request->filled('month')) {
            $bookings->whereMonth('booking_dt', $request->month);
        }

        $bookings = $bookings->get();

        $years = Booking::selectRaw('YEAR(booking_dt) as year')
            ->distinct()
            ->orderByDesc('year')
            ->pluck('year');

        $months = collect();
        if ($request->filled('year')) {
            $months = Booking::whereYear('booking_dt', $request->year)
                ->selectRaw('MONTH(booking_dt) as month')
                ->distinct()
                ->pluck('month');
        }

        return view('admin.modules.calendar.calendar_view', compact(
            'calendars', 'bookings', 'years', 'months', 'selectedCalendarId'
        ));
    }

    public function fetchBookings(Request $request)
    {
        \Log::info('Fetch Bookings Request:', $request->all());

        $user = Auth::guard('admin')->user();
        if (!$user || !in_array($user->user_type_id, [1, 2])) {
            return response()->json(['error' => 'Unauthorized access'], 403);
        }

        if ($user->user_type_id == 1) {
            $allowedCalendarIds = Booking::distinct()->pluck('calendar_id')->toArray();
        }
        elseif ($user->user_type_id == 2) {
            $hasAllCalendar = DB::table('user_calendar')
                ->where('user_id', $user->id)
                ->where('calendar_id', 1)
                ->exists();

            if ($hasAllCalendar) {
                $allowedCalendarIds = Booking::distinct()->pluck('calendar_id')->toArray();
            } else {
                $allowedCalendarIds = DB::table('user_calendar')
                    ->where('user_id', $user->id)
                    ->pluck('calendar_id')
                    ->toArray();
            }
        }

        $bookings = Booking::whereIn('calendar_id', $allowedCalendarIds);

        if ($request->filled('calendar_id')) {
            \Log::info('Filtering by Calendar ID:', ['calendar_id' => $request->calendar_id]);
            $bookings->where('calendar_id', $request->calendar_id);
        }

        if (!$request->filled('year')) {
            $request->merge(['year' => now()->year]);
        }

        if ($request->filled('year')) {
            \Log::info('Filtering by Year:', ['year' => $request->year]);
            $bookings->whereYear('booking_dt', $request->year);
        }

        if ($request->filled('month')) {
            \Log::info('Filtering by Month:', ['month' => $request->month]);
            $bookings->whereMonth('booking_dt', $request->month);
        }

        $bookingsData = $bookings->orderBy('booking_dt', 'asc')->get();

        \Log::info('Final Filtered Bookings:', $bookingsData->toArray());

        return response()->json($bookingsData->map(function ($booking) {
            return [
                'id' => $booking->id,
                'title' => $booking->client_1 ?? 'Booking',
                'booking_dt' => $booking->booking_dt,
                'calendar_id' => $booking->calendar_id,
                'color' => match ($booking->booking_status) {
                    1 => 'green',
                    0, 3 => 'purple',
                    4 => 'red',
                    default => 'gray',
                },
            ];
        }));
    }

    public function viewBooking($id)
    {
        $booking = Booking::with('calendar', 'user')->findOrFail($id); // 🔹 Booking data fetch karna
        return view('admin.modules.calendar.view_booking', compact('booking'));
    }


}
