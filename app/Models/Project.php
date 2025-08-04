<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'start_date',
        'end_date',
        'status',
        'budget',
        'lead_researcher',
        'leader',
        'funding_agency',
        'is_active',
        'is_visible',
        'image',
        'added_by',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'is_active' => 'boolean',
        'is_visible' => 'boolean',
        'budget' => 'decimal:2',
    ];
}
