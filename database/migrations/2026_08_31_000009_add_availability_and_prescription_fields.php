<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('appointments', function (Blueprint $table) {
            $table->foreignId('availability_id')->nullable()->after('doctor_id')->constrained('doctor_availabilities')->nullOnDelete(); });
        Schema::table('medical_records', function (Blueprint $table) {
            $table->text('prescription')->nullable()->after('treatment'); });
    }
    public function down()
    {
        Schema::table('appointments', function (Blueprint $table) {
            $table->dropForeign(['availability_id']);
            $table->dropColumn('availability_id'); });
        Schema::table('medical_records', function (Blueprint $table) {
            $table->dropColumn('prescription'); });
    }
};