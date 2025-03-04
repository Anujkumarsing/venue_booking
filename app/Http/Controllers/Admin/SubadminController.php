<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Models\User;
use App\Models\Calendar;
use App\Mail\SubAdminLoginInfoMail;
use Illuminate\Support\Facades\Mail;
class SubadminController extends Controller
{
    public function index()
    {
        $subadmins = User::with('calendars')
        ->where('user_type_id', 2)
        ->orderBy('created_at', 'desc')
        ->paginate(10);

        return view('admin.modules.sub_admin.manage_subadmin', compact('subadmins'));
    }


    public function save_subadmin(Request $request, $id = null)
{
    $data = [];

    if ($request->all()) {
        if ($id) {
            $validated = $request->validate([
                'fname' => ['required', 'string'],
                'lname' => ['required', 'string'],
                'email' => ['required', 'email', Rule::unique('users')->ignore($id)],
                'password' => 'nullable|min:6',
                'calendar_ids' => 'array|required|min:1',
                'calendar_ids.*' => 'integer|exists:calendars,id',
            ]);
        } else {
            $validated = $request->validate([
                'fname' => 'required|string',
                'lname' => 'required|string',
                'email' => 'required|email|unique:users,email',
                'password' => 'required|min:6',
                'calendar_ids' => 'array|required|min:1',
                'calendar_ids.*' => 'integer|exists:calendars,id',
            ]);
        }
        $userData = [
            'fname' => $validated['fname'],
            'lname' => $validated['lname'],
            'email' => $validated['email'],
            'is_email_verified' => 1,
            'user_type_id' => 2,
            'status' => 1,
        ];
        if (!empty($validated['password'])) {
            $userData['password'] = bcrypt($validated['password']);
        }

        if ($id) {
            $user = User::findOrFail($id);
            $user->update($userData);
            $user->calendars()->sync($validated['calendar_ids']);
            return redirect()->route('manage.sub.admin')->with('success', 'Subadmin updated successfully.');
        } else {
            $userData['password'] = bcrypt($validated['password']);
            $user = User::create($userData);
            $user->calendars()->attach($validated['calendar_ids']);

            Mail::to($user->email)->send(new SubAdminLoginInfoMail($user, $validated['password']));

            return redirect()->route('manage.sub.admin')->with('success', 'Subadmin added successfully.');
        }
    }

    $data['calendars'] = Calendar::all();
    if ($id) {
        $data['subadmin'] = User::with('calendars')->find($id);
    }

    return view('admin.modules.sub_admin.save_subadmin')->with($data);
}

    public function toggleStatus($id)
{
    $user = User::findOrFail($id);
    $user->status = !$user->status;
    $user->save();
    return redirect()->route('manage.sub.admin')->with('success', 'Subadmin status updated.');
}
public function deleteSubadmin($id)
{
    try {
        $user = User::findOrFail($id);
        $user->calendars()->detach();
        $user->delete();
        return redirect()->route('manage.sub.admin')->with('success', 'Subadmin deleted successfully.');
    } catch (\Exception $e) {
        return redirect()->route('manage.sub.admin')->with('error', 'Failed to delete Subadmin. Please try again.');
    }
}
public function checkEmailUnique(Request $request)
{
    $email = $request->input('email');
    $id = $request->input('id');

    $exists = User::where('email', $email)
                  ->when($id, function ($query, $id) {
                      return $query->where('id', '!=', $id);
                  })
                  ->exists();

    return response()->json(!$exists);
}

}
