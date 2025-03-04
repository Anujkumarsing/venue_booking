<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ImportOldDatabase extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:import-old-database';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Migrate data from old database to new Laravel database';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting data migration from old database...');

         $oldUsers = DB::connection('mysql_old')->table('users')->get();
        foreach ($oldUsers as $user) {
        $newUserType = match ($user->user_type_id) {
        1 => 1,
        2 => 3,
        3 => 2,
        default => $user->user_type_id,
       };

    DB::connection('mysql')->table('users')->updateOrInsert(
        ['email' => $user->email],
        [
            'id' => $user->user_id,
            'fname' => $user->fname,
            'lname' => $user->lname,
            'password' => $user->password,
            'user_type_id' => $newUserType,
            'last_login' => $user->last_login,
            'vcode' => $user->vcode ?? '',
            'status' => $user->status,
            'is_email_verified' => $user->is_email_verified,
            'created_at' => $user->dt_join > 0 ? Carbon::createFromTimestamp($user->dt_join) : now(),
            'updated_at' => now(),
            'remember_token' => null,
        ]
    );
     }
      $this->info('Users migrated successfully with updated user_type_id mappings.');


         $oldCalendars = DB::connection('mysql_old')->table('calendar')->get();
        foreach ($oldCalendars as $calendar) {
            DB::connection('mysql')->table('calendars')->updateOrInsert([
                'id' => $calendar->calendar_id,
                'calendar_name' => $calendar->calendar_name,
                'email' => $calendar->email ?? '',
                'status' => $calendar->status,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
        $this->info('Calendars migrated successfully.');

         // Bookings Table Migration
          $oldBookings = DB::connection('mysql_old')->table('booking')->get();
        foreach ($oldBookings as $booking) {
            DB::connection('mysql')->table('bookings')->updateOrInsert([
                'id' => $booking->booking_id, // Mapping `booking_id` to `id`
                'user_id' => $booking->user_id,
                'admin_id' => $booking->admin_id,
                'client_1' => $booking->client_1,
                'client_2' => $booking->client_2,
                'email' => $booking->email,
                'phone' => $booking->phone,
                'guest_count' => $booking->guest_count,
                'notes' => $booking->notes,
                'file' => $booking->file,
                'booking_dt' => $booking->booking_dt,
                'booking_status' => $booking->booking_status,
                'calendar_id' => $booking->calendar_id,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
        $this->info('Bookings migrated successfully.');

        $oldUserToCalendars = DB::connection('mysql_old')->table('user_to_calendar')->get();
        foreach ($oldUserToCalendars as $relation) {
            // Check if user exists before inserting
            DB::connection('mysql')->table('user_calendar')->updateOrInsert(
                ['id' => $relation->id],
                [
                    'user_id' => $relation->user_id,
                    'calendar_id' => $relation->calendar_id,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
        }
        $this->info('User to Calendar relationships migrated successfully.');

         $this->info('Data migration completed successfully.');
    }
}
