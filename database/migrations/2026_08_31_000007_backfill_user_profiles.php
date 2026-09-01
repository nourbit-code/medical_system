<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up()
    {
        DB::statement("INSERT INTO doctors (user_id, first_name, last_name, specialization, phone, email, created_at, updated_at) SELECT u.id, SUBSTRING_INDEX(u.name, ' ', 1), CASE WHEN INSTR(u.name, ' ') > 0 THEN SUBSTRING(u.name, INSTR(u.name, ' ') + 1) ELSE '' END, 'General Medicine', 'Not provided', u.email, NOW(), NOW() FROM users u LEFT JOIN doctors d ON d.user_id = u.id WHERE u.role = 'doctor' AND d.id IS NULL");
        DB::statement("INSERT INTO patients (user_id, first_name, last_name, phone, email, gender, created_at, updated_at) SELECT u.id, SUBSTRING_INDEX(u.name, ' ', 1), CASE WHEN INSTR(u.name, ' ') > 0 THEN SUBSTRING(u.name, INSTR(u.name, ' ') + 1) ELSE '' END, 'Not provided', u.email, 'other', NOW(), NOW() FROM users u LEFT JOIN patients p ON p.user_id = u.id WHERE u.role = 'patient' AND p.id IS NULL");
    }

    public function down()
    {
    }
};