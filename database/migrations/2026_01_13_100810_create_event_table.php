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
            $table->string('event_id', 20)->primary();
            $table->string('org_id', 20);
            $table->string('title', 150);
            $table->text('description')->nullable();
            $table->dateTime('event_date');
            $table->integer('event_duration')->default(4);
            $table->string('venue', 100)->nullable();
            $table->enum('status', ['Pending', 'Upcoming', 'Ongoing', 'Done', 'Cancelled'])->default('Pending');
            $table->timestamp('created_at')->useCurrent();
            $table->string('created_by', 20)->nullable();

            $table->foreign('org_id')->references('org_id')->on('organizations')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('created_by')->references('user_id')->on('users')->nullOnDelete()->onUpdate('cascade');
        });

        Schema::create('event_attendance', function (Blueprint $table) {
            $table->string('attendance_id', 20)->primary();
            $table->string('event_id', 20);
            $table->string('user_id', 20);
            $table->enum('status', ['RSVP', 'Walk-in', 'Present', 'Absent', 'Excused'])->default('RSVP');
            $table->text('remarks')->nullable();

            $table->foreign('event_id')->references('event_id')->on('events')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('user_id')->references('user_id')->on('users')->onDelete('cascade')->onUpdate('cascade');
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
