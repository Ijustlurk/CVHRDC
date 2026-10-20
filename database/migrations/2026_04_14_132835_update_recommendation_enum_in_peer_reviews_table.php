<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // MySQL requires a raw ALTER to change enum values
        DB::statement("ALTER TABLE peer_reviews MODIFY COLUMN recommendation ENUM(
            'Accept Submission',
            'Revisions Required (Minor)',
            'Resubmit for Review',
            'Resubmit Elsewhere',
            'Decline Submission'
        ) NULL DEFAULT NULL");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("ALTER TABLE peer_reviews MODIFY COLUMN recommendation ENUM(
            'Accept',
            'Minor Revision',
            'Major Revision',
            'Reject'
        ) NULL DEFAULT NULL");
    }
};
