<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DatabaseBackupSetting extends Model
{
    protected $fillable = [
        'frequency', // in days
        'last_backup', // datetime
    ];
}
