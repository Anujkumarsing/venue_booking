<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Calendar;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class BookingController extends Controller
{
    public function index(Request $request)
{
    $user = Auth::guard('admin')->user();
    if (!$user || !in_array($user->user_type_id, [1, 2])) {
        abort(403, 'Unauthorized access');
    }
    if ($user->user_type_id == 1) {
        $calendars = Calendar::where('id', '!=', 1)->get();
    }
    elseif ($user->user_type_id == 2) {
        $hasAllCalendar = DB::table('user_calendar')
            ->where('user_id', $user->id)
            ->where('calendar_id', 1)
            ->exists();

        if ($hasAllCalendar) {
            $calendars = Calendar::where('id', '!=', 1)->get();
        } else {
            $calendars = Calendar::whereIn('id', function ($query) use ($user) {
                $query->select('calendar_id')
                    ->from('user_calendar')
                    ->where('user_id', $user->id);
            })->get();
        }
    }
    $bookings = Booking::with(['calendar', 'user']);

    if ($user->user_type_id == 2 && !$hasAllCalendar) {
        $bookings = $bookings->whereIn('calendar_id', $calendars->pluck('id'));
    }

    if ($request->filled('calendar_id')) {
        $bookings = $bookings->where('calendar_id', $request->calendar_id);
    }

    if ($request->filled('status')) {
        if ($request->status == 'all_awaiting') {
            $bookings = $bookings->whereIn('booking_status', [0, 3]);
        } else {
            $bookings = $bookings->where('booking_status', $request->status);
        }
    }
    if ($request->filled('from_date') || $request->filled('to_date')) {
        $fromDate = $request->input('from_date') ? date('Y-m-d 00:00:00', strtotime($request->from_date)) : null;
        $toDate = $request->input('to_date') ? date('Y-m-d 23:59:59', strtotime($request->to_date)) : null;

        if ($fromDate && !$toDate) {
            $bookings = $bookings->where('booking_dt', '>=', $fromDate);
        } elseif ($toDate && !$fromDate) {
            $bookings = $bookings->where('booking_dt', '<=', $toDate);
        } elseif ($fromDate && $toDate) {
            $bookings = $bookings->whereBetween('booking_dt', [$fromDate, $toDate]);
        }
    }
    $bookings = $bookings->orderBy('id', 'desc')->paginate(20);

    return view('admin.modules.booking.manage_booking', compact('bookings', 'calendars'));
}


    public function saveBooking(Request $request, $id = null)
{
    $data = [];

    if ($request->isMethod('post')) {
        $validated = $request->validate([
            'calendar_id' => 'required',
            'user_id'     => 'required|exists:users,id',
            'client_1'    => 'required|string',
            'client_2'    => 'nullable|string',
            'email'       => 'required|email|max:255',
            'phone'       => 'required|string',
            'guest_count' => 'required',
            'notes'       => 'nullable|string',
            'file'        => 'nullable|file|mimes:jpg,jpeg,png,pdf',
            'booking_dt'  => 'required|date_format:d-m-Y',
        ]);

        $validated['booking_dt'] = Carbon::createFromFormat('d-m-Y', $validated['booking_dt'])->format('Y-m-d');

        $existingBooking = Booking::where('calendar_id', $validated['calendar_id'])
            ->whereDate('booking_dt', $validated['booking_dt'])
            ->where('id', '!=', $id)
            ->exists();

        if ($existingBooking) {
            return redirect()->back()->withErrors(['booking_dt' => 'A booking for this calendar on this date already exists.']);
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
            'admin_id'       => Auth::guard('admin')->id(),
            'calendar_id'    => $validated['calendar_id'],
            'user_id'        => $validated['user_id'],
            'client_1'       => $validated['client_1'],
            'client_2'       => $validated['client_2'],
            'email'          => $validated['email'],
            'phone'          => $validated['phone'],
            'guest_count'    => $validated['guest_count'],
            'notes'          => $validated['notes'],
            'file'           => $fileName,
            'booking_dt'     => $validated['booking_dt'],
            'booking_status' => 1,
        ];

        if ($id) {
            if ($request->has('action') && $request->action == 'approve_update') {
                $bookingData['booking_status'] = 1;
            }
            Booking::where('id', $id)->update($bookingData);
            return redirect()->back()->with('success', 'Booking updated successfully.');
        } else {
            Booking::create($bookingData);
            return redirect()->back()->with('success', 'Booking added successfully.');
        }
    }

    $admin = Auth::guard('admin')->user();
    $adminId = $admin->id;

    $assignedCalendars = DB::table('user_calendar')
        ->where('user_id', $adminId)
        ->pluck('calendar_id')
        ->toArray();

    if ($admin->user_type_id == 2) {
        $hasAllCalendar = in_array(1, $assignedCalendars);

        if ($hasAllCalendar) {
            $data['calendars'] = Calendar::with(['users' => function ($query) {
                $query->where('user_type_id', 3);
            }])->where('id', '!=', 1)->get();
        } else {
            $data['calendars'] = Calendar::with(['users' => function ($query) {
                $query->where('user_type_id', 3);
            }])->whereIn('id', $assignedCalendars)->get();
        }
       } else {
        $data['calendars'] = Calendar::with(['users' => function ($query) {
            $query->where('user_type_id', 3);
         }])->where('id', '!=', 1)->get();
       }

    $data['booking'] = $id ? Booking::find($id) : null;

    return view('admin.modules.booking.save_booking', $data);
}


    public function approveBooking($id)
    {
        $booking = Booking::find($id);
        if ($booking) {
            $booking->booking_status = 1;
            $booking->save();
        }
        return redirect()->back()->with('success', 'Booking approved successfully.');
    }

    public function rejectBooking($id)
    {
        $booking = Booking::find($id);
        if ($booking) {
            $booking->booking_status = 2;
            $booking->save();
        }
        return redirect()->back()->with('error', 'Booking rejected successfully.');
    }
    public function show($id)
    {

        $booking = Booking::with(['calendar', 'user'])->findOrFail($id);
        // dd($booking);
        return view('admin.modules.booking.view_booking', compact('booking'));
    }
    public function bulkAction(Request $request)
    {
    $action = $request->action;
    $bookingIds = $request->booking_ids;


    if (!$action || empty($bookingIds)) {
        return response()->json(['message' => 'Invalid request!'], 400);
    }

    try {
        if ($action === 'approve') {
            Booking::whereIn('id', $bookingIds)->update(['booking_status' => 1]);
            return response()->json(['message' => 'Selected bookings have been approved.'], 200);
        } elseif ($action === 'reject') {
            Booking::whereIn('id', $bookingIds)->update(['booking_status' => 2]);
            return response()->json(['message' => 'Selected bookings have been rejected.'], 200);
        } else {
            return response()->json(['message' => 'Invalid action specified!'], 400);
        }
    } catch (\Exception $e) {
        return response()->json(['message' => 'An error occurred: ' . $e->getMessage()], 500);
    }
    }
     public function cancelBooking($id)
     {
    $booking = Booking::findOrFail($id);

    if ($booking->booking_status == 1) {
        $booking->booking_status = 4;
        $booking->save();

        return redirect()->back()->with('success', 'Booking has been cancelled successfully.');
    }
    return redirect()->back()->with('error', 'Booking cannot be cancelled at this stage.');
   }
   public function awaitingApprovalBookings(Request $request)
   {
       $user = Auth::guard('admin')->user();
       if (!$user || !in_array($user->user_type_id, [1, 2])) {
           abort(403, 'Unauthorized access');
       }
       if ($user->user_type_id == 1) {
           $calendars = Calendar::where('id', '!=', 1)->get();
       }
       elseif ($user->user_type_id == 2) {
           $hasAllCalendar = DB::table('user_calendar')
               ->where('user_id', $user->id)
               ->where('calendar_id', 1)
               ->exists();

           if ($hasAllCalendar) {
               $calendars = Calendar::where('id', '!=', 1)->get();
           } else {
               $calendars = Calendar::whereIn('id', function ($query) use ($user) {
                   $query->select('calendar_id')
                       ->from('user_calendar')
                       ->where('user_id', $user->id);
               })->get();
           }
       }
       $bookings = Booking::whereIn('booking_status', [0, 3])->with(['calendar', 'user']);

       if ($user->user_type_id == 2 && !$hasAllCalendar) {
           $bookings = $bookings->whereIn('calendar_id', $calendars->pluck('id'));
       }
       if ($request->filled('calendar_id')) {
           $bookings = $bookings->where('calendar_id', $request->calendar_id);
       }

       $bookings = $bookings->paginate(5);

       return view('admin.modules.booking.awaiting-approval-bookings', compact('bookings', 'calendars'));
   }

   public function delete($id)
  {
    $booking = Booking::find($id);

    if (!$booking) {
        return redirect()->back()->with('error', 'Booking not found.');
    }
    $booking->delete();

    return redirect()->back()->with('success', 'Booking deleted successfully.');
  }

  public function booking_print(){
    $bookings = Booking::where('booking_status', '0')->with(['calendar', 'user']);
    return view('admin.modules.booking.print_preview', compact('bookings'));
  }


  public function approved_booking(Request $request)
  {
      $user = Auth::guard('admin')->user();
      if (!$user || !in_array($user->user_type_id, [1, 2])) {
          abort(403, 'Unauthorized access');
      }
      if ($user->user_type_id == 1) {
          $calendars = Calendar::where('id', '!=', 1)->get();
      }
      elseif ($user->user_type_id == 2) {
          $hasAllCalendar = DB::table('user_calendar')
              ->where('user_id', $user->id)
              ->where('calendar_id', 1)
              ->exists();

          if ($hasAllCalendar) {
              $calendars = Calendar::where('id', '!=', 1)->get();
          } else {
              $calendars = Calendar::whereIn('id', function ($query) use ($user) {
                  $query->select('calendar_id')
                      ->from('user_calendar')
                      ->where('user_id', $user->id);
              })->get();
          }
      }
      $bookings = Booking::where('booking_status', '1')->with(['calendar', 'user']);
      if ($user->user_type_id == 2 && !$hasAllCalendar) {
          $bookings = $bookings->whereIn('calendar_id', $calendars->pluck('id'));
      }
      if ($request->filled('calendar_id')) {
          $bookings = $bookings->where('calendar_id', $request->calendar_id);
      }

      $bookings = $bookings->paginate(10);

      return view('admin.modules.booking.approved_booking', compact('bookings', 'calendars'));
  }

  public function cancel_booking(Request $request)
  {
      $user = Auth::guard('admin')->user();
      if (!$user || !in_array($user->user_type_id, [1, 2])) {
          abort(403, 'Unauthorized access');
      }
      if ($user->user_type_id == 1) {
          $calendars = Calendar::where('id', '!=', 1)->get();
      }
      elseif ($user->user_type_id == 2) {
          $hasAllCalendar = DB::table('user_calendar')
              ->where('user_id', $user->id)
              ->where('calendar_id', 1)
              ->exists();

          if ($hasAllCalendar) {
              $calendars = Calendar::where('id', '!=', 1)->get();
          } else {
              $calendars = Calendar::whereIn('id', function ($query) use ($user) {
                  $query->select('calendar_id')
                      ->from('user_calendar')
                      ->where('user_id', $user->id);
              })->get();
          }
      }
      $bookings = Booking::where('booking_status', '4')->with(['calendar', 'user']);

      if ($user->user_type_id == 2 && !$hasAllCalendar) {
          $bookings = $bookings->whereIn('calendar_id', $calendars->pluck('id'));
      }
      if ($request->filled('calendar_id')) {
          $bookings = $bookings->where('calendar_id', $request->calendar_id);
      }

      $bookings = $bookings->paginate(10);

      return view('admin.modules.booking.cancel_booking', compact('bookings', 'calendars'));
  }

  public function Awaitingshow($id)
  {
      $booking = Booking::with(['calendar', 'user'])->findOrFail($id);
      return view('admin.modules.booking.view_awaiting_booking', compact('booking'));
  }
  public function Approvedshow($id)
  {
      $booking = Booking::with(['calendar', 'user'])->findOrFail($id);
      // dd($booking);
      return view('admin.modules.booking.view_approved_booking', compact('booking'));
  }
  public function Cancelshow($id)
  {
      $booking = Booking::with(['calendar', 'user'])->findOrFail($id);
      // dd($booking);
      return view('admin.modules.booking.view_cancel_booking', compact('booking'));
  }
  public function booking_report(Request $request)
  {
      $user = Auth::guard('admin')->user();
      if (!$user || !in_array($user->user_type_id, [1, 2])) {
          abort(403, 'Unauthorized access');
      }
      if ($user->user_type_id == 1) {
          $calendars = Calendar::where('id', '!=', 1)->get();
      }
      elseif ($user->user_type_id == 2) {
          $hasAllCalendar = DB::table('user_calendar')
              ->where('user_id', $user->id)
              ->where('calendar_id', 1)
              ->exists();

          if ($hasAllCalendar) {
              $calendars = Calendar::where('id', '!=', 1)->get();
          } else {
              $calendars = Calendar::whereIn('id', function ($query) use ($user) {
                  $query->select('calendar_id')
                      ->from('user_calendar')
                      ->where('user_id', $user->id);
              })->get();
          }
      }
      $bookings = Booking::with(['calendar', 'user']);

      if ($user->user_type_id == 2 && !$hasAllCalendar) {
          $bookings = $bookings->whereIn('calendar_id', $calendars->pluck('id'));
      }

      if ($request->filled('calendar_id')) {
          $bookings = $bookings->where('calendar_id', $request->calendar_id);
      }

      if ($request->filled('status')) {
          $bookings = $bookings->where('booking_status', $request->status);
      }

      if ($request->filled('from_date') || $request->filled('to_date')) {
          $fromDate = $request->input('from_date') ? date('Y-m-d 00:00:00', strtotime($request->from_date)) : null;
          $toDate = $request->input('to_date') ? date('Y-m-d 23:59:59', strtotime($request->to_date)) : null;

          if ($fromDate && !$toDate) {
              $bookings = $bookings->where('booking_dt', '>=', $fromDate);
          } elseif ($toDate && !$fromDate) {
              $bookings = $bookings->where('booking_dt', '<=', $toDate);
          } elseif ($fromDate && $toDate) {
              $bookings = $bookings->whereBetween('booking_dt', [$fromDate, $toDate]);
          }
      }

      $bookings = $bookings->orderBy('id', 'desc')->get();

      return view('admin.modules.report.booking_report', compact('bookings', 'calendars'));
  }

}
