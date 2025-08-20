<?php
// database/migrations/2024_01_01_000002_create_quotes_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('quotes', function (Blueprint $table) {
            $table->id();
            $table->string('reference_number')->unique();
            $table->string('name');
            $table->string('email');
            $table->string('phone')->nullable();
            $table->enum('service_type', ['residential', 'commercial', 'custom-builds', 'renovations']);
            $table->text('project_details');
            $table->decimal('estimated_budget', 15, 2)->nullable();
            $table->date('preferred_start_date')->nullable();
            $table->string('location')->nullable();
            $table->json('attachments')->nullable(); // File attachments
            $table->enum('status', ['new', 'reviewed', 'quoted', 'accepted', 'rejected'])->default('new');
            $table->enum('priority', ['low', 'medium', 'high'])->default('medium');
            $table->text('internal_notes')->nullable();
            $table->decimal('quoted_amount', 15, 2)->nullable();
            $table->date('quote_valid_until')->nullable();
            $table->timestamp('responded_at')->nullable();
            $table->foreignId('assigned_to')->nullable()->constrained('users');
            $table->timestamps();
            
            $table->index(['status', 'priority']);
            $table->index(['service_type', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('quotes');
    }
};