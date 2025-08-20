<?php
// database/migrations/2024_01_01_000001_create_projects_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('description');
            $table->text('short_description');
            $table->enum('category', ['residential', 'commercial', 'custom-builds', 'renovations']);
            $table->enum('status', ['planning', 'in-progress', 'completed', 'on-hold'])->default('planning');
            $table->decimal('budget', 15, 2)->nullable();
            $table->date('start_date')->nullable();
            $table->date('completion_date')->nullable();
            $table->string('location')->nullable();
            $table->string('client_name')->nullable();
            $table->json('gallery')->nullable(); // Store image paths as JSON
            $table->json('before_after')->nullable(); // Before/after images
            $table->text('case_study')->nullable();
            $table->json('features')->nullable(); // Project features as JSON
            $table->boolean('is_featured')->default(false);
            $table->boolean('is_published')->default(true);
            $table->integer('sort_order')->default(0);
            $table->json('seo_meta')->nullable(); // SEO metadata
            $table->timestamps();
            
            $table->index(['category', 'status']);
            $table->index(['is_featured', 'is_published']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('projects');
    }
};