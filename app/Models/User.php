<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'address',
        'campus',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];


    public function roles()
    {
        return $this->belongsToMany('App\Models\Role');
        
    }

    public function hasAnyRoles($roles)
    {
        if($this->roles()->whereIn('name', $roles)->first()){
            return true;
        }
        return false;

    }

    public function hasRole($role)
    {
        if($this->roles()->where('name', $role)->first()){
            return true;
        }
        return false;

    }

    /**
     * Check if user is a Super Admin
     *
     * @return bool
     */
    public function isSuperAdmin()
    {
        return $this->hasRole('superadmin');
    }

    /**
     * Check if user is an Admin
     *
     * @return bool
     */
    public function isAdmin()
    {
        return $this->hasRole('admin');
    }

    /**
     * Check if user is RMC (Research Management Committee)
     *
     * @return bool
     */
    public function isRMC()
    {
        return $this->hasRole('RMC');
    }

    /**
     * Check if user is RUC (Research Utilization Committee)
     *
     * @return bool
     */
    public function isRUC()
    {
        return $this->hasRole('RUC');
    }

    /**
     * Get all calls created by this user.
     */
    public function createdCalls()
    {
        return $this->hasMany(CallForProposal::class, 'created_by');
    }

    /**
     * Get all calls approved by this user.
     */
    public function approvedCalls()
    {
        return $this->hasMany(CallForProposal::class, 'approved_by');
    }
}
