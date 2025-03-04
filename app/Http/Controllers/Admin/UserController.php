<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\UserLoginInfoMail;
use App\Models\Calendar;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rule;

class UserController extends Controller
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

        $query = User::with('calendars')->where('user_type_id', 3);

        if ($user->user_type_id == 2 && !$hasAllCalendar) {
            $query->whereHas('calendars', function ($q) use ($calendars) {
                $q->whereIn('calendars.id', $calendars->pluck('id'));
            });
        }

        if ($request->filled('calendar_id')) {
            $query->whereHas('calendars', function ($q) use ($request) {
                $q->where('calendars.id', $request->calendar_id);
            });
        }

        $query->orderBy('id', 'desc');
        $users = $query->paginate(20);

        return view('admin.modules.users.manage_users', [
            'users'            => $users,
            'calendars'        => $calendars,
            'selectedCalendar' => $request->calendar_id,
        ]);
    }
    public function save_users(Request $request, $id = null)
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

    $data = ['calendars' => $calendars];

    if ($id) {
        $data['user'] = User::with('calendars')->findOrFail($id);

        if ($user->user_type_id == 2) {
            $allowedUserIds = DB::table('user_calendar')
                ->whereIn('calendar_id', $calendars->pluck('id'))
                ->pluck('user_id');

            if (!$allowedUserIds->contains($id)) {
                abort(403, 'Unauthorized access');
            }
        }
    }

        if ($request->isMethod('post')) {
            $rules = [
                'fname'          => 'required|string|max:255',
                'lname'          => 'required|string|max:255',
                'email'          => ['required', 'email', Rule::unique('users')->ignore($id)],
                'password'       => $id ? 'nullable|min:6' : 'required|min:6',
                'calendar_ids'   => 'required|array|min:1',
                'calendar_ids.*' => 'integer|exists:calendars,id|not_in:1',
            ];

            $validated = $request->validate($rules);

            // Prepare user data
            $userData = [
                'fname'             => $validated['fname'],
                'lname'             => $validated['lname'],
                'email'             => $validated['email'],
                'password'          => $validated['password'] ? bcrypt($validated['password']) : ($id ? $data['user']->password : null),
                'is_email_verified' => 1,
                'user_type_id'      => 3,
                'status'            => 1,
            ];

            if ($id) {
                // Update user if an ID is provided
                $user = User::findOrFail($id);
                $user->update($userData);
                $user->calendars()->sync($validated['calendar_ids']);
                return redirect()->route('manage.users')->with('success', 'User updated successfully.');
            } else {
                // Create a new user if no ID is provided
                $user = User::create($userData);
                $user->calendars()->attach($validated['calendar_ids']);

                // Send email to the user
                Mail::to($user->email)->send(new UserLoginInfoMail($user, $validated['password']));

                return redirect()->route('manage.users')->with('success', 'User added successfully. Login info sent to user.');
            }
        }

        // Return the view with the user data and calendars
        return view('admin.modules.users.save_users', $data);
    }

    public function toggleStatus($id)
    {
        $user         = User::findOrFail($id);
        $user->status = ! $user->status;
        $user->save();
        return redirect()->route('manage.users')->with('success', 'User status updated successfully.');
    }
    public function deleteUser($id)
    {
        try {
            $user = User::findOrFail($id);
            $user->calendars()->detach();
            $user->delete();
            return redirect()->route('manage.users')->with('success', 'User deleted successfully.');
        } catch (\Exception $e) {
            return redirect()->route('manage.users')->with('error', 'Failed to delete user. Please try again.');
        }
    }
    public function checkEmail(Request $request)
    {
        $emailExists = User::where('email', $request->email)
            ->when($request->user_id, function ($query) use ($request) {
                $query->where('id', '!=', $request->user_id);
            })
            ->exists();

        return response()->json(! $emailExists);
    }

}
