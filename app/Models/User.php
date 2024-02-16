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

    public function files()
    {
        return $this->hasMany(File::class);
    }

    public function documents()
    {
        return $this->hasMany(Document::class);
    }

    public function timeout()
    {
        return $this->hasOne(Timeout::class);
    }

    public function timeouts()
    {

        return collect([
            'user' => optional($this->timeout)->timeout,
            'global' => GlobalTimeout::all()->first()->timeout,
        ]);
    }

    public function scopeAdmins($query)
    {
        return $query->whereHas('roles', function ($query) {

            $query->where('name', '=', 'admin');

        });
    }

    public function scopeUsers($query)
    {
        return $query->doesntHave('roles');
    }

    public function hasRole($role)
    {
        if (is_string($role)) {
            dd($this->roles);
            return $this->roles->contains('name', $role);
        }

        return !! $role->intersect($this->roles)->count();
    }

    public function getCreatedAtAttribute($value)
    {
        return \Carbon\Carbon::parse($value)->format('m.d.Y');
    }
}
