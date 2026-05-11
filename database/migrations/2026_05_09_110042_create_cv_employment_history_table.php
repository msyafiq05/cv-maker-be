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
        Schema::create('cv_employment_history', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cv_project_id')->constrained('cv_projects')->cascadeOnDelete();
            $table->string('company_name', 150);
            $table->string('job_title', 150);
            $table->string('start_year', 20)->nullable();
            $table->string('end_year', 20)->nullable();
            $table->string('company_location', 150)->nullable();
            $table->text('company_description')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cv_employment_history');
    }
};
