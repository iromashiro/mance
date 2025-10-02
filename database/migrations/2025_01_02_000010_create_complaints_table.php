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
        Schema::create('complaints', function (Blueprint $table) {
            $table->id();
            $table->string('ticket_number', 20)->unique(); // Format: CPL-YYYYMMDD-XXXX
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('category_id')->references('id')->on('complaint_categories')->onDelete('cascade');
            $table->string('title', 500);
            $table->text('description');
            $table->text('location_address')->nullable();
            $table->decimal('location_lat', 10, 8)->nullable();
            $table->decimal('location_lng', 11, 8)->nullable();
            $table->enum('status', ['pending', 'in_progress', 'resolved', 'rejected'])->default('pending');
            $table->enum('priority', ['low', 'normal', 'high', 'urgent'])->default('normal');
            $table->boolean('is_public')->default(true);
            $table->timestamp('resolved_at')->nullable();
            $table->text('resolution_note')->nullable();
            $table->integer('satisfaction_rating')->nullable()->check('satisfaction_rating >= 1 AND satisfaction_rating <= 5');
            $table->timestamps();

            // Indexes
            $table->index('status');
            $table->index('user_id');
            $table->index('category_id');
            $table->index('ticket_number');
            $table->index(['created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('complaints');
    }
};
