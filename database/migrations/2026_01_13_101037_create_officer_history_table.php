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
        Schema::create('officer_history', function (Blueprint $table) {
            $table->string('history_id', 20)->primary();
            $table->string('user_id', 20);
            $table->string('org_id', 20);
            $table->string('position', 50)->nullable();
            $table->date('term_start')->nullable();
            $table->date('term_end')->nullable();
            $table->timestamp('archived_at')->useCurrent();

            $table->foreign('user_id')->references('user_id')->on('users')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('officer_history');
    }
};
