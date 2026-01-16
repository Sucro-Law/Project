<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->string('user_id', 20)->primary(); // VARCHAR(20) with PN- prefix
            $table->string('school_id', 20)->unique()->nullable();
            $table->string('full_name', 100);
            $table->string('email', 100)->unique();
            $table->string('password');
            $table->enum('account_type', ['Student', 'Faculty', 'Admin']);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};