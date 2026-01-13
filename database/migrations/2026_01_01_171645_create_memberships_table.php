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
        Schema::create('memberships', function (Blueprint $table) {
            $table->id('membership_id');
            $table->foreignId('user_id')->constrained('users', 'user_id')->onDelete('cascade');
            $table->foreignId('org_id')->constrained('organizations', 'org_id')->onDelete('cascade');
            $table->string('academic_year', 20);
            $table->enum('membership_role', ['Officer', 'Member'])->default('Member');
            $table->date('joined_at')->useCurrent();
            $table->enum('status', ['Pending', 'Active', 'Alumni', 'Rejected'])->default('Pending');
            
            // Unique constraint: A user can only join an org once
            $table->unique(['user_id', 'org_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('memberships');
    }
};
