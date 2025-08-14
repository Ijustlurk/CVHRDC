<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Book extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'file_path', 'file_original_name', 'file_mime', 'file_size',
        'title', 'authors', 'editors', 'published_at', 'publisher', 'isbn',
        'edition', 'doi', 'keywords', 'is_public',
    ];

    protected $casts = [
        'authors' => 'array',
        'editors' => 'array',
        'keywords' => 'array',
        'is_public' => 'boolean',
        'published_at' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}


