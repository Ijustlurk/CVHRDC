<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CallForProposal extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'call_for_proposals';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'title',
        'description',
        'research_priority_area',
        'eligibility',
        'guidelines',
        'funding_limit',
        'proposal_format_file',
        'start_date',
        'end_date',
        'status',
        'visibility_status',
        'created_by',
        'approved_by',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'funding_limit' => 'decimal:2',
        'status' => 'string',
        'visibility_status' => 'string',
    ];

    /**
     * Get the user who created the call for proposal.
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the user who approved the call for proposal.
     */
    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    /**
     * Get all proposal submissions for this call.
     */
    public function proposalSubmissions(): HasMany
    {
        return $this->hasMany(ProposalSubmission::class, 'call_id');
    }
}

