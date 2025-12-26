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
        Schema::create('proposal_submissions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('call_id')->constrained('call_for_proposals')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('research_title', 255);
            $table->string('proponent_name', 255);
            $table->string('affiliation', 255);
            $table->string('email', 100);
            $table->string('contact_number', 20);
            $table->string('proposal_document', 255);
            $table->decimal('budget_requested', 12, 2);
            $table->timestamp('date_submitted')->useCurrent();
            $table->enum('status', ['Submitted', 'Under Review', 'Accepted', 'Rejected'])->default('Submitted');
            $table->text('remarks')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('proposal_submissions');
    }
};

