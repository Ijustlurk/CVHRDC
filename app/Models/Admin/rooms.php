<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class rooms extends Model
{
    use HasFactory;
protected $fillable=[
    'roomid',
    'roomarea',
    'roomcapacity',
    'bldgid',
    'datecompleted'
];

}
