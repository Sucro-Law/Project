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
        Schema::create('org_alumni', function (Blueprint $table) {
            $table->id('history_id');
            $table->foreignId('user_id')->constrained('users', 'user_id')->onDelete('cascade');
            $table->foreignId('org_id')->constrained('organizations', 'org_id')->onDelete('cascade');
            $table->string('academic_year', 20)->nullable();
            $table->string('status', 20)->default('Alumni');
            $table->timestamp('archived_at')->useCurrent();
        });

        Schema::create('officer_history', function (Blueprint $table) {
            $table->id('history_id');
            $table->foreignId('user_id')->constrained('users', 'user_id')->onDelete('cascade');
            $table->foreignId('org_id')->constrained('organizations', 'org_id')->onDelete('cascade');
            $table->string('position', 50)->nullable();
            $table->date('term_start')->nullable();
            $table->date('term_end')->nullable();
            $table->timestamp('archived_at')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('officer_history');
        Schema::dropIfExists('org_alumni');
    }
};
