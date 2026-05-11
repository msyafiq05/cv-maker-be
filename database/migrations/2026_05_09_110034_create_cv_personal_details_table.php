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
        Schema::create('cv_personal_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cv_project_id')->constrained('cv_projects')->cascadeOnDelete();
            $table->string('full_name', 150);
            $table->string('phone_number', 20);
            $table->string('email_address', 100);
            $table->string('place_of_birth', 100);
            $table->string('date_of_birth', 50);
            $table->text('address')->nullable();
            $table->string('website_url', 255)->nullable();
            $table->string('short_description', 150)->nullable(); 
            $table->string('foto_profil', 255)->nullable(); 
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cv_personal_details');
    }
};
