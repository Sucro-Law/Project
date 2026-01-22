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
            $table->string('officer_id', 20)->primary();
            $table->string('membership_id', 20);
            $table->string('org_id', 20);
            $table->string('position', 50);
            $table->date('term_start')->nullable();
            $table->date('term_end')->nullable();

            $table->foreign('membership_id')->references('membership_id')->on('memberships')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('org_id')->references('org_id')->on('organizations')->onDelete('cascade')->onUpdate('cascade');
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
