<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable;

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

    public function roles()
    {
        return $this->hasMany(Role::class,'user_id','user_id');
    }
    public function notices()
    {
        return $this->hasMany(Notice::class,'user_id','build_id');
    }
    public function permissions()
    {
        return $this->hasMany(Permission::class,'user_id','user_id');
    }
    public function unit()
    {
        return $this->belongsTo(Unit::class,'unit_id','id');
    }
    public function hiddens()
    {
        return $this->hasMany(Hidden::class,'user_id','user_id');
    }
    public function chemicals()
    {
        return $this->hasMany(Chemical::class,'user_id','user_id');
    }

}
