<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        if (Schema::hasTable('doctor_availabilities')) {
            Schema::table('doctor_availabilities', function (Blueprint $table) {
                $table->unique(['doctor_id', 'available_date', 'available_time'], 'doctor_slot_unique'); });
            return;
        }
        Schema::create('doctor_availabilities', function (Blueprint $table) {
            $table->id();
            $table->foreignId('doctor_id')->constrained()->cascadeOnDelete();
            $table->date('available_date');
            $table->time('available_time');
            $table->boolean('is_booked')->default(false);
            $table->timestamps();
            $table->unique(['doctor_id', 'available_date', 'available_time'], 'doctor_slot_unique');
        });
    }
    public function down()
    {
        Schema::dropIfExists('doctor_availabilities');
    }
};