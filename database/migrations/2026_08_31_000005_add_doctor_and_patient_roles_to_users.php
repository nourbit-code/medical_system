<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up()
    {
        DB::statement("ALTER TABLE users MODIFY role ENUM('admin', 'receptionist', 'doctor', 'patient') NOT NULL DEFAULT 'patient'");
        DB::table('users')->where('role', 'receptionist')->update(['role' => 'admin']);
        DB::statement("ALTER TABLE users MODIFY role ENUM('admin', 'doctor', 'patient') NOT NULL DEFAULT 'patient'");
    }

    public function down()
    {
        DB::statement("ALTER TABLE users MODIFY role ENUM('admin', 'receptionist', 'doctor', 'patient') NOT NULL DEFAULT 'patient'");
    }
};