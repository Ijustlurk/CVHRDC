<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Footer extends Model
{
    protected $fillable = [
        'org_name',
        'description',
        'address',
        'email',
        'phone',
        'website',
        'copyright',
    ];
}
