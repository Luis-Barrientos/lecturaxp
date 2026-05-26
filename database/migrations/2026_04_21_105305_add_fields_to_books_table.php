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
        Schema::table('books', function (Blueprint $table) {
                $table->string('cover_url')->nullable()->after('genre');
                $table->text('description')->nullable()->after('cover_url');
                $table->integer('published_year')->nullable()->after('description');
                $table->enum('status',['active', 'archived'])->default('active')->after('published_year');
                $table->decimal('average_rating', 3, 1)->default(0)->after('status');
                $table->integer('ratings_count')->default(0)->after('average_rating');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('books', function (Blueprint $table) {
            $table->dropColumn([
                'cover_url', 
                'description', 
                'published_year', 
                'status', 
                'average_rating', 
                'ratings_count'
            ]);
        });
    }
};
