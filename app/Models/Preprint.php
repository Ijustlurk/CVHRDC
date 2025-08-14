<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Preprint extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'file_path',
        'file_original_name',
        'file_mime',
        'file_size',
        'title',
        'authors',
        'published_at',
        'repository_name',
        'abstract',
        'keywords',
        'is_peer_reviewed',
        'is_public',
    ];

    protected $casts = [
        'authors' => 'array',
        'keywords' => 'array',
        'is_peer_reviewed' => 'boolean',
        'is_public' => 'boolean',
        'published_at' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
