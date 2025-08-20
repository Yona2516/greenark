<?php
// database/migrations/2024_01_01_000003_create_messages_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('messages', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email');
            $table->string('phone')->nullable();
            $table->string('subject');
            $table->text('message');
            $table->enum('type', ['general', 'support', 'complaint', 'inquiry'])->default('general');
            $table->enum('status', ['new', 'read', 'replied', 'archived'])->default('new');
            $table->enum('priority', ['low', 'medium', 'high'])->default('medium');
            $table->timestamp('read_at')->nullable();
            $table->timestamp('replied_at')->nullable();
            $table->text('admin_reply')->nullable();
            $table->foreignId('assigned_to')->nullable()->constrained('users');
            $table->timestamps();
            
            $table->index(['status', 'priority']);
            $table->index(['type', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('messages');
    }
};