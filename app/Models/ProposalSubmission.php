<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProposalSubmission extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'proposal_submissions';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'call_id',
        'research_title',
        'proponent_name',
        'affiliation',
        'email',
        'contact_number',
        'proposal_document',
        'budget_requested',
        'date_submitted',
        'status',
        'remarks',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'budget_requested' => 'decimal:2',
        'date_submitted' => 'datetime',
        'status' => 'string',
    ];

    /**
     * Get the call for proposal this submission belongs to.
     */
    public function callForProposal(): BelongsTo
    {
        return $this->belongsTo(CallForProposal::class, 'call_id');
    }
}

