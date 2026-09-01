<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
    public function up() { Schema::create('medical_records', function (Blueprint $table) {
        $table->id(); $table->foreignId('appointment_id')->constrained()->cascadeOnDelete(); $table->text('diagnosis')->nullable(); $table->text('symptoms')->nullable(); $table->text('notes')->nullable(); $table->text('treatment')->nullable(); $table->timestamps();
    }); }
    public function down() { Schema::dropIfExists('medical_records'); }
};
