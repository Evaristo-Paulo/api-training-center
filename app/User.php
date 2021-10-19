<?php

namespace App;

use App\Models\Role;
use App\Models\Gender;
use App\Models\Secretary;
use App\Models\Trainee;
use App\Models\Trainer;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable implements JWTSubject
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function trainer(){
        return $this->hasOne(Trainer::class);
    }

    public function secretary(){
        return $this->hasOne(Secretary::class);
    }

    public function trainee(){
        return $this->hasOne(Trainee::class);
    }

    public function gender()
    {
        return $this->belongsTo(Gender::class);
    }
    
    public function roles()
    {
        return $this->belongsToMany ( Role::class, 'role_users', 'user_id', 'role_id');
    }

    public function hasAnyRoles($roles)
    {
        if ($this->roles()->whereIn('type', $roles)->first()) {
            return true;
        }

        return false;
    }
    public function hasRole($role)
    {
        if ($this->roles()->where('type', $role)->first()) {
            return true;
        }

        return false;
    }

    public function getJWTIdentifier()
    {
        return $this->getKey();   
    }

    public function getJWTCustomClaims()
    {
        return [];
    }
}
