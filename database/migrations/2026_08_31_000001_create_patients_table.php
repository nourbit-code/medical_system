<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
    public function up()
    {
        Schema::create('patients', function (Blueprint $table) {
            $table->id();
            $table->string('first_name');
            $table->string('last_name');
            $table->string('phone');
            $table->string('email')->nullable();
            $table->date('date_of_birth')->nullable();
            $table->string('gender');
            $table->text('address')->nullable();
            $table->string('emergency_contact')->nullable();
            $table->timestamps();
        });
    }
    public function down()
    {
        Schema::dropIfExists('patients');
    }
};