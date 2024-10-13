<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Enums\RoleName;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, HasRoles, Notifiable;

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

    public function appointments(): HasMany
    {
        return $this->hasMany(Appointment::class);
    }

    public function isReceptionist(): bool
    {
        return $this->hasRole(RoleName::RECEPTIONIST);
    }

    public function isVeterinary(): bool
    {
        return $this->hasRole(RoleName::VETERINARY);
    }

    public function scopeRoleIs(Builder $query, RoleName $roleName): Builder
    {
        return $query->withWhereHas('roles', function ($query) use ($roleName) {
            $query->where('name', $roleName->value);
        });
    }

    public function scopeReceptionists(Builder $query): Builder
    {
        return $query->RoleIs(RoleName::RECEPTIONIST);
    }

    public function scopeVeterinaries(Builder $query): Builder
    {
        return $query->RoleIs(RoleName::VETERINARY);
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
}
