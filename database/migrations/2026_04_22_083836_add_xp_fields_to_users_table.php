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
        Schema::table('users', function (Blueprint $table) {
            $table->bigInteger('total_xp')->default(0)->after('role');
            $table->integer('current_level')->default(1)->after('total_xp');
            $table->integer('streak_days')->default(0)->after('current_level');
            $table->date('last_read_date')->nullable()->after('streak_days');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'total_xp',
                'current_level',
                'streak_days',
                'last_read_date',
            ]);
        });
    }
};
