<?php
// database/migrations/2024_01_01_000004_create_media_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('media', function (Blueprint $table) {
            $table->id();
            $table->string('filename');
            $table->string('original_filename');
            $table->string('mime_type');
            $table->bigInteger('size'); // in bytes
            $table->string('path');
            $table->string('url');
            $table->json('metadata')->nullable(); // dimensions, alt text, etc.
            $table->morphs('mediable'); // polymorphic relationship
            $table->string('collection')->default('default');
            $table->timestamps();
            
            $table->index(['mediable_type', 'mediable_id']);
            $table->index(['collection']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('media');
    }
};