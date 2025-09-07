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
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->string('type', 50); // success, warning, info, danger
            $table->string('title');
            $table->text('message');
            $table->string('icon', 100)->default('fas fa-bell');
            $table->unsignedBigInteger('user_id')->nullable(); // null for system-wide notifications
            $table->boolean('is_read')->default(false);
            $table->timestamp('read_at')->nullable();
            $table->json('data')->nullable(); // additional data for the notification
            $table->string('action_url')->nullable(); // URL to redirect when clicked
            $table->enum('priority', ['low', 'medium', 'high'])->default('medium');
            $table->timestamp('expires_at')->nullable(); // for auto-expiring notifications
            $table->timestamps();
            
            // Indexes for better performance
            $table->index(['user_id', 'is_read']);
            $table->index(['created_at']);
            $table->index(['type']);
            
            // Foreign key constraint
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notifications');
    }
};
