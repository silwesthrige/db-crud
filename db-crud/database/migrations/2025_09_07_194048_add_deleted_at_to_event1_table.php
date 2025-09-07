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
        Schema::table('event1', function (Blueprint $table) {
            $table->softDeletes(); // This adds deleted_at timestamp column
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('event1', function (Blueprint $table) {
            $table->dropSoftDeletes(); // This removes deleted_at column
        });
    }
};
