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
        Schema::create('call_for_proposals', function (Blueprint $table) {
            $table->id();
            $table->string('title', 255);
            $table->text('description');
            $table->string('research_priority_area', 255);
            $table->text('eligibility');
            $table->text('guidelines');
            $table->decimal('funding_limit', 12, 2);
            $table->string('proposal_format_file', 255)->nullable();
            $table->date('start_date');
            $table->date('end_date');
            $table->enum('status', ['Draft', 'For Review', 'Open', 'Closed'])->default('Draft');
            $table->enum('visibility_status', ['Private', 'Public'])->default('Private');
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            $table->foreignId('approved_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('call_for_proposals');
    }
};

