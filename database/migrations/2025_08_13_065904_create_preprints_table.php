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
        Schema::create('preprints', function (Blueprint $table) {
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
            $table->string('repository_name'); // e.g., arXiv, bioRxiv, institutional repository
            $table->text('abstract')->nullable();
            $table->json('keywords')->nullable();

            // Preprint specific
            $table->boolean('is_peer_reviewed')->default(false);

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
        Schema::dropIfExists('preprints');
    }
};
