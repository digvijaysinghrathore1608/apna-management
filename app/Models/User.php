<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'status',
        'last_login_at',
        'is_banned',
        'mobile',
        'terms_accepted',
    ];
    // Relationships

    public function isSuperAdmin(): bool
    {
        return $this->roles()->where('name', 'super_admin')->exists();
    }

    public function roles()
    {
        return $this->belongsToMany(Role::class, 'user_service_roles')->withTimestamps();
    }

    public function services()
    {
        return $this->belongsToMany(Service::class, 'user_service_roles')->withTimestamps();
    }

    public function userServiceRoles()
    {
        return $this->hasMany(UserServiceRole::class);
    }

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'last_login_at' => 'datetime',
            'is_banned' => 'boolean',
            'terms_accepted' => 'boolean',
            'password' => 'hashed',
        ];
    }

    public function isActive(): bool
    {
        return $this->status === 'active' && !$this->is_banned;
    }

    public function hasRole($role, $service = null): bool
    {
        $query = $this->userServiceRoles()->whereHas('role', function ($q) use ($role) {
            $q->where('name', $role);
        });
        if ($service) {
            $query->whereHas('service', function ($q) use ($service) {
                $q->where('name', $service);
            });
        }
        return $query->exists();
    }

    public function hasService($service): bool
    {
        return $this->services()->where('name', $service)->exists();
    }

    public function hasServiceRole(string $service, string $role): bool
    {
        return $this->userServiceRoles()
            ->whereHas('service', fn($q) => $q->where('name', $service))
            ->whereHas('role', fn($q) => $q->where('name', $role))
            ->exists();
    }
}
