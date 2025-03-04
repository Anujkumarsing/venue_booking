<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UpdateUserPasswordSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        DB::table('users')->where('email', 'info@venuebookingcalendar.com')->update([
            'password' => Hash::make('admin'),
            'updated_at' => now(),
        ]);

        echo "Password updated successfully!";
    }
}
