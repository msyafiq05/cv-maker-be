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
        Schema::create('cv_education', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100); 
            $table->string('username', 50)->unique();
            $table->string('email', 100)->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password')->nullable();
            $table->string('avatar', 255)->nullable();
            $table->string('phone_number', 20)->nullable();
            $table->string('country', 100)->nullable();
            $table->string('skills', 255)->nullable();
            $table->text('about')->nullable();
            $table->string('social_media', 255)->nullable();
            $table->enum('role', ['admin', 'user'])->default('user');
            $table->string('google_id')->nullable();
            $table->string('reset_token')->nullable();
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cv_education');
    }
};
