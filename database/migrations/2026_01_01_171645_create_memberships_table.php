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
            $table->string('membership_id', 20)->primary();
            $table->string('user_id', 20);
            $table->string('org_id', 20);
            $table->string('academic_year', 20);
            $table->enum('membership_role', ['Officer', 'Member'])->default('Member');
            $table->date('joined_at')->useCurrent();
            $table->enum('status', ['Pending', 'Active', 'Rejected', 'Alumni'])->default('Pending');

            $table->foreign('user_id')->references('user_id')->on('users')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('org_id')->references('org_id')->on('organizations')->onDelete('cascade')->onUpdate('cascade');
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
