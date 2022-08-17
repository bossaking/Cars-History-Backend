<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'surname',
        'email',
        'password',
        'active'
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
    ];

    public function roles()
    {
        return $this->belongsToMany(Role::class);
    }

    public function getMaxRole() : string
    {
        if($this->isOwner()){
            return 'owner';
        }

        if($this->isAdmin()){
            return 'admin';
        }

        if($this->isMechanic()){
            return 'mechanic';
        }

        return 'user';
    }

    public function isOwner() : bool
    {
        return $this->roles()->get()->where('name', 'OWNER')->first() != null;
    }

    public function isAdmin() : bool
    {
        return $this->roles()->get()->where('name', 'ADMIN')->first() != null;
    }

    public function isMechanic() : bool
    {
        return $this->roles()->get()->where('name', 'MECHANIC')->first() != null;
    }

}
