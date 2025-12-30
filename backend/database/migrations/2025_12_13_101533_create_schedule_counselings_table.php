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
        Schema::create('schedule_counselings', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('request_id')->constrained('request_counselings')->onDelete('cascade');
            $table->foreignUuid('counselor_id')->constrained('users')->onDelete('cascade');

            $table->enum('method', ['chat', 'face-to-face']);

            $table->date('schedule_date');
            $table->time('time_slot');

            $table->enum('status', ['pending', 'confirmed', 'canceled', 'completed'])->default('pending');

            $table->timestamp('confirmed_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('schedule_counselings');
    }
};
