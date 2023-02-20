<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

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
        'email',
        'password',
        'discord_id'
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
        return $this->belongsToMany(Role::class, 'user_role');
    }

    public function getPermissionsAttribute()
    {
        return $this->roles->map->permissions->flatten()->map(function ($permission) {
            return $permission->name;
        })->unique();
    }

    public function hasPermission(string $permission): bool
    {
        $hasPermission = $this->permissions->contains($permission);

        if ($hasPermission) {
            return true;
        }

        $permission = explode('-', $permission);
        return $this->permissions->contains($permission[0] . "-admin");
    }
}
