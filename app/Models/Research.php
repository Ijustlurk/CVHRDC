<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Research extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'research';

    protected $fillable = [
        'user_id',
        'file_path',
        'file_original_name',
        'file_mime',
        'file_size',
        'title',
        'authors',
        'published_at',
        'abstract',
        'category',
        'keywords',
        'is_public',
    ];

    protected $casts = [
        'authors' => 'array',
        'keywords' => 'array',
        'is_public' => 'boolean',
        'published_at' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
