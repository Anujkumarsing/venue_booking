<?php

use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\CalenderController;
use App\Http\Controllers\Admin\SubadminController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\BookingController;
use App\Http\Middleware\AdminAuth;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;


// Route::get('/clear-cache', function() {

//     $clearCache = Artisan::call('cache:clear');
//     $configCache = Artisan::call('config:cache');
//     $configClear = Artisan::call('config:clear');;
//     return '<h1>Cache cleared</h1>';
// });


//Login
// Route::get('/', [AuthController::class, 'showLoginForm'])->name('admin.login');
// Route::post('/login', [AuthController::class, 'login'])->name('admin.login.submit');

//Logout
// Route::any('/logout', [AuthController::class, 'logout'])->name('admin.logout');

//Change Password and Email
// Route::any('/change-email', [AuthController::class, 'changeEmail'])->name('change.email');
// Route::any('/change-password', [AuthController::class, 'changePassword'])->name('change.password');

//Forgot Password
// Route::get('/forgot/password', [AuthController::class, 'showForgotPasswordForm'])->name('admin.forgot.password');
// Route::post('/forgot/password', [AuthController::class, 'postForgotPassword'])->name('admin.forgot.password.post');
// Route::get('/reset/password/{token}', [AuthController::class, 'resetPassword'])->name('admin.reset.password');
// Route::post('/reset/password/', [AuthController::class, 'postresetPassword'])->name('admin.reset.password.post');


// Route::prefix('admin')->middleware('admin.auth')->group(function () {
//     Route::get('/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');

     //Manage Calender
    //  Route::any('/manage-calendar', [CalenderController ::class, 'index'])->name('manage.calendar');
    //  Route::any('/save-calendar/{id?}', [CalenderController::class, 'saveCalendar'])->name('save.calendar');
    //  Route::get('/delete-calendar/{id}', [CalenderController::class, 'deleteCalendar'])->name('delete.calendar');
    //  Route::get('/toggle-calendar-status/{id}', [CalenderController::class, 'toggleCalendarStatus'])->name('toggle.calendar.status');
    //  Route::any('/calendar/view', [CalenderController ::class, 'calendar_view'])->name('calendar_view');
    //  Route::any('/calendar/bookings', [CalenderController::class, 'fetchBookings'])->name('calendar.fetchBookings');

     //Manage Subadmin
    //  Route::any('/manage/sub-admin', [SubadminController ::class, 'index'])->name('manage.sub.admin');
    //  Route::any('/save/sub-admin/{id?}', [SubadminController::class, 'save_subadmin'])->name('save.sub.admin');
    //  Route::get('/toggle-status/sub-admin/{id}', [SubadminController::class, 'toggleStatus'])->name('toggle.status.sub.admin');
    //  Route::get('/delete/sub-admin/{id}', [SubadminController::class, 'deleteSubadmin'])->name('delete.sub.admin');
    //  Route::post('/check/email-unique', [SubadminController::class, 'checkEmailUnique'])->name('check.email.unique');


     //Manage Users
    //  Route::any('/manage/users', [UserController ::class, 'index'])->name('manage.users');
    //  Route::any('/save/users/{id?}', [UserController::class, 'save_users'])->name('save.users');
    //  Route::get('/toggle-status/user/{id}', [UserController::class, 'toggleStatus'])->name('toggle.status.user');
    //  Route::get('/delete/user/{id}', [UserController::class, 'deleteUser'])->name('delete.user');
    //  Route::post('/check-email', [UserController::class, 'checkEmail'])->name('check.email');

    //Manage Booking
    // Route::any('/manage/booking', [BookingController ::class, 'index'])->name('manage.booking');
    // Route::any('/save-booking/{id?}', [BookingController::class, 'saveBooking'])->name('save.booking');
    // Route::any('/booking/approve/{id}', [BookingController::class, 'approveBooking'])->name('approve.booking');
    // Route::any('/booking/reject/{id}', [BookingController::class, 'rejectBooking'])->name('reject.booking');
    // Route::get('/booking/{id}/view', [BookingController::class, 'show'])->name('view.booking');
    // Route::post('/bulk-action', [BookingController::class, 'bulkAction'])->name('bulk.action');
    // Route::get('cancel-booking/{id}', [BookingController::class, 'cancelBooking'])->name('cancel.booking');

    //Awaiting Apprroval
    // Route::any('/awaiting-approval', [BookingController::class, 'awaitingApprovalBookings'])->name('awaiting.approval');
    // Route::get('/delete-booking/{id}', [BookingController::class, 'delete'])->name('delete.booking');
    // Route::any('/view/booking_print', [BookingController::class, 'booking_print'])->name('booking_print');
    // Route::get('/awaiting-approval/booking/{id}/view', [BookingController::class, 'Awaitingshow'])->name('awaiting.view.booking');

    // Approved Booking
    // Route::any('/approved/booking', [BookingController::class, 'approved_booking'])->name('approved.booking');
    // Route::get('/approved/booking/{id}/view', [BookingController::class, 'Approvedshow'])->name('approved.view.booking');

    // Cancel Booking
    // Route::any('/cancel/booking', [BookingController::class, 'cancel_booking'])->name('cancelled.booking');
    // Route::get('/cancel/booking/{id}/view', [BookingController::class, 'Cancelshow'])->name('cancelled.view.booking');

    //Booking Report
    // Route::any('/booking/report', [BookingController ::class, 'booking_report'])->name('booking.report');

// });

