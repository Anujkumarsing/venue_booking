<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('admin.auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        $credentials = $request->only('email', 'password');
        $remember = $request->has('remember');

        $user = User::where('email', $request->email)->first();

        if ($user) {
            if (!Hash::needsRehash($user->password)) {
                if (!Hash::check($request->password, $user->password)) {
                    return back()->withErrors(['email' => 'Invalid credentials.']);
                }
            } else {
                if ($user->password === $request->password) {
                    $user->password = Hash::make($request->password);
                    $user->save();
                } else {
                    return back()->withErrors(['email' => 'Invalid credentials.']);
                }
            }

            if ($user->status != 1) {
                return back()->withErrors(['email' => 'Your account is inactive. Please contact the admin.']);
            }

            if ($user->user_type_id == 1 || $user->user_type_id == 2) {
                Auth::guard('admin')->login($user, $remember);
            } elseif ($user->user_type_id == 3) {
                Auth::guard('user')->login($user, $remember);
            } else {
                return back()->withErrors(['email' => 'Unauthorized access.']);
            }

            if ($remember) {
                Cookie::queue('email', $request->email, 43200);
                Cookie::queue('password', $request->password, 43200);
            } else {
                Cookie::queue(Cookie::forget('email'));
                Cookie::queue(Cookie::forget('password'));
            }

            return $user->user_type_id == 1 || $user->user_type_id == 2
                ? redirect()->route('admin.dashboard')
                : redirect()->route('user.dashboard');
        }

        return back()->withErrors(['email' => 'Invalid credentials or unauthorized access.']);
    }
    public function logout()
    {
        if (Auth::guard('admin')->check()) {
            Auth::guard('admin')->logout();
            return redirect()->route('admin.login')->with('success', 'Logged out successfully.');
        }

        if (Auth::guard('user')->check()) {
            Auth::guard('user')->logout();
            return redirect()->route('user.dashboard')->with('success', 'Logged out successfully.');
        }

        return redirect('/');
    }
    public function changeEmail(Request $request)
    {
        $user = Auth::guard('admin')->check() ? Auth::guard('admin')->user() : Auth::guard('user')->user();

        if (!$user) {
            return redirect()->route('admin.login');
        }

        if ($request->isMethod('post')) {
            $request->validate([
                'email' => 'required|email|unique:users,email,' . $user->id,
            ]);
            $user->email = $request->email;
            $user->save();

            return redirect()->back()->with('success', 'Email updated successfully.');
        }

        return view('admin.auth.change_email');
    }


    public function changePassword(Request $request)
    {
        $user = Auth::guard('admin')->check() ? Auth::guard('admin')->user() : Auth::guard('user')->user();

        if (!$user) {
            return redirect()->route('admin.login');
        }

        if ($request->isMethod('post')) {
            $request->validate([
                'current_password' => 'required',
                'new_password' => 'required|min:5|confirmed',
            ]);

            if (!Hash::check($request->current_password, $user->password)) {
                return back()->withErrors(['current_password' => 'The current password is incorrect.']);
            }

            $user->password = Hash::make($request->new_password);
            $user->save();

            return redirect()->back()->with('success', 'Password updated successfully.');
        }

        return view('admin.auth.change_password');
        }


    /**
     * Forgot Password - Show Form
     */
    public function showForgotPasswordForm()
    {
        return view('admin.auth.forgot_password');
    }

    /**
     * Forgot Password - Send Reset Link
     */
    public function postForgotPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users',
        ]);

        $token = Str::random(64);

        DB::table('password_resets')->insert([
            'email' => $request->email,
            'token' => $token,
            'created_at' => Carbon::now(),
        ]);

        Mail::send("admin.email.forget_password", ['token' => $token], function ($message) use ($request) {
            $message->to($request->email);
            $message->subject("Reset Password");
        });

        return redirect()->route('admin.forgot.password')->with("success", "We have sent an email to reset Password.");
    }

    /**
     * Show Reset Password Form
     */
    public function resetPassword($token)
    {
        return view('admin.auth.new_password', compact('token'));
    }

    /**
     * Reset Password
     */
    public function postResetPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users',
            'password' => 'required|min:5|confirmed',
            'password_confirmation' => 'required',
        ]);

        $updatePassword = DB::table('password_resets')->where([
            "email" => $request->email,
            "token" => $request->token,
        ])->first();

        if (!$updatePassword) {
            return redirect()->route('admin.reset.password')->with("error", "Invalid or expired token.");
        }

        User::where("email", $request->email)
            ->update(["password" => Hash::make($request->password)]);

        DB::table('password_resets')->where(["email" => $request->email])->delete();

        return redirect()->route('admin.login')->with("success", "Password Reset Successfully.");
    }
}
