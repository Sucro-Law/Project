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
        Schema::create('events', function (Blueprint $table) {
            $table->id('event_id');
            $table->foreignId('org_id')->constrained('organizations', 'org_id')->onDelete('cascade');
            $table->string('title', 150);
            $table->text('description')->nullable();
            $table->dateTime('event_date');
            $table->integer('event_duration')->default(4);
            $table->string('venue', 100)->nullable();
            $table->enum('status', ['Pending', 'Upcoming', 'Ongoing', 'Done', 'Cancelled'])->default('Pending');
            $table->timestamp('created_at')->useCurrent();

            $table->foreignId('created_by')->nullable()->constrained('users', 'user_id')->nullOnDelete();
        });

        Schema::create('event_attendance', function (Blueprint $table) {
            $table->id('attendance_id');
            $table->foreignId('event_id')->constrained('events', 'event_id')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users', 'user_id')->onDelete('cascade');
            $table->enum('status', ['RSVP', 'Walk-in', 'Present', 'Absent', 'Excused'])->default('RSVP');
            $table->text('remarks')->nullable();

            $table->unique(['event_id', 'user_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('event_attendance');
        Schema::dropIfExists('events');
    }
};
