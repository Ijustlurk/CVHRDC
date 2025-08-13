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
        Schema::create('conference_papers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();

            // File details
            $table->string('file_path');
            $table->string('file_original_name');
            $table->string('file_mime', 100)->default('application/pdf');
            $table->unsignedBigInteger('file_size');

            // Metadata
            $table->string('title');
            $table->json('authors');
            $table->date('published_at')->nullable();
            $table->string('conference_name');
            $table->string('conference_location');
            $table->string('publisher')->nullable();
            $table->text('abstract')->nullable();
            $table->string('language', 5)->nullable(); // ISO 639-1
            $table->json('keywords')->nullable();

            // Visibility
            $table->boolean('is_public')->default(true);

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('conference_papers');
    }
};
