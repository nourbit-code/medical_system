<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up()
    {
        DB::statement("ALTER TABLE appointments MODIFY status ENUM('pending','confirmed','in_progress','completed','cancelled') NOT NULL DEFAULT 'pending'");
    }
    public function down()
    {
        DB::statement("ALTER TABLE appointments MODIFY status ENUM('pending','confirmed','completed','cancelled') NOT NULL DEFAULT 'pending'");
    }
};