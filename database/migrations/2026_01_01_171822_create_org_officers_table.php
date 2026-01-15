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
        Schema::create('org_officers', function (Blueprint $table) {
            $table->id('officer_id');
            $table->foreignId('membership_id')->constrained('memberships', 'membership_id')->onDelete('cascade');
            $table->foreignId('org_id')->constrained('organizations', 'org_id');
            $table->string('position', 50);
            $table->date('term_start')->nullable();
            $table->date('term_end')->nullable();

            $table->unique('membership_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('org_officers');
    }
};
