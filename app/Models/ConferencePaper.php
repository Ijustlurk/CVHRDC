<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ConferencePaper extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'file_path', 'file_original_name', 'file_mime', 'file_size',
        'title', 'authors', 'published_at',
        'conference_name', 'conference_location', 'publisher',
        'abstract', 'language', 'keywords', 'is_public',
    ];

    protected $casts = [
        'authors' => 'array',
        'keywords' => 'array',
        'is_public' => 'boolean',
        'published_at' => 'date',
    ];
}
