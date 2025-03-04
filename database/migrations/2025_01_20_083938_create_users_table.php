<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('fname');
            $table->string('lname');
            $table->string('email')->unique();
            $table->integer('is_email_verified')->nullable()->comment('1-email verified'); // Adding comment for is_email_verified
            $table->string('password');
            $table->integer('user_type_id')->comment('1-Admin, 2-Subadmin, 3-Users');      // Adding comment for user_type_id
            $table->integer('last_login');
            $table->string('vcode');
            $table->integer('status')->comment('1-Active, 2-Inactive');                    // Adding comment for status
            $table->rememberToken();
            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
