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

    /**
     * Get the display status of the call based on dates and current status.
     * This determines how the call should be presented to the public.
     */
    public function getDisplayStatusAttribute(): string
    {
        $now = now();

        // If the call is not public, don't show it
        if ($this->visibility_status !== 'Public') {
            return 'Private';
        }

        // Check date-based status first
        if ($now->isBefore($this->start_date)) {
            return 'Upcoming';
        }

        if ($now->isAfter($this->end_date)) {
            return 'Closed';
        }

        // Then check explicit status
        return match($this->status) {
            'Open' => 'Open',
            'Closed' => 'Closed',
            'For Review', 'Draft' => 'Upcoming',
            default => 'Closed'
        };
    }

    /**
     * Check if the call is currently accepting submissions.
     */
    public function isAcceptingSubmissions(): bool
    {
        return $this->display_status === 'Open' && $this->visibility_status === 'Public';
    }

    /**
     * Get the badge color for the display status.
     */
    public function getStatusBadgeColor(): string
    {
        return match($this->display_status) {
            'Open' => 'success',
            'Closed' => 'secondary',
            'Upcoming' => 'warning',
            default => 'light'
        };
    }

    /**
     * Get the status label for display.
     */
    public function getStatusLabel(): string
    {
        return match($this->display_status) {
            'Open' => 'Open',
            'Closed' => 'Closed',
            'Upcoming' => 'Upcoming',
            default => 'Not Available'
        };
    }

    /**
     * Automatically update call status based on dates.
     * This should be called periodically or on access.
     */
    public function updateStatusBasedOnDates(): bool
    {
        $now = now();
        $updated = false;

        // If end date has passed and call is still Open, close it
        if ($now->isAfter($this->end_date) && $this->status === 'Open') {
            $this->status = 'Closed';
            $updated = true;
        }

        // If start date hasn't arrived and call is Open, keep it as is
        // (status should be managed by RUC, not automatically changed)

        if ($updated) {
            $this->save();
        }

        return $updated;
    }
}

