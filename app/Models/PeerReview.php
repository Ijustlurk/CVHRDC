<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PeerReview extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'proposal_submission_id',
        'reviewer_id',
        'assigned_by',
        'status',
        'recommendation',
        'comments',
        'review_file',
    ];

    public function submission()
    {
        return $this->belongsTo(ProposalSubmission::class, 'proposal_submission_id');
    }

    public function reviewer()
    {
        return $this->belongsTo(User::class, 'reviewer_id');
    }

    public function coordinator()
    {
        return $this->belongsTo(User::class, 'assigned_by');
    }
}
