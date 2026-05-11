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
        Schema::create('cv_organizations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cv_project_id')->constrained('cv_projects')->cascadeOnDelete();
            $table->string('organization_name', 150);
            $table->string('role', 100);
            $table->string('start_year', 20)->nullable();
            $table->string('end_year', 20)->nullable();
            $table->string('location', 150)->nullable();
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cv_organizations');
    }
};
