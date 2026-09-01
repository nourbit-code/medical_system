<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        if (! Schema::hasColumn('doctors', 'date_of_birth')) {
            Schema::table('doctors', function (Blueprint $table) {
                $table->date('date_of_birth')->nullable()->after('email');
            });
        }
    }

    public function down()
    {
        if (Schema::hasColumn('doctors', 'date_of_birth')) {
            Schema::table('doctors', function (Blueprint $table) {
                $table->dropColumn('date_of_birth');
            });
        }
    }
};
