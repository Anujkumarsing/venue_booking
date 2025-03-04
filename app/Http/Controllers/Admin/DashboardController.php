<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Calendar;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
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
                ->paginate(10);
        } elseif ($user->user_type_id == 2) {
            $hasAllCalendar = DB::table('user_calendar')
                ->where('user_id', $user->id)
                ->where('calendar_id', 1)
                ->exists();

            if ($hasAllCalendar) {
                $calendars = Calendar::where('id', '!=', 1)
                    ->orderBy('created_at', 'desc')
                    ->paginate(10);
            } else {
                $calendars = $user->calendars()
                    ->orderBy('created_at', 'desc')
                    ->paginate(10);
            }
        }
        return view('admin.modules.calendar.manage_calendar', compact('calendars'));
    }
}
