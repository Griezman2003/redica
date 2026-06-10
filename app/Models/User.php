<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Filament\Models\Contracts\FilamentUser; // IMPORTANTE: Descomentado
use Filament\Panel;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements FilamentUser // IMPLEMENTACIÓN ACTIVA
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

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
     * @var array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Relación con los clientes asignados a este usuario.
     */
    public function cliente()
    {
        return $this->hasMany(Cliente::class);
    }

    /**
     * Control de acceso al Panel de Filament (Integración con Shield).
     */
    public function canAccessPanel(Panel $panel): bool
    {
        // 1. Siempre permitimos el acceso en entorno local para desarrollo (Laragon)
        if (app()->environment('local')) {
            return true;
        }

        // 2. En producción, verificamos si el usuario tiene un rol asignado a través de Spatie/Shield
        // Esto evita que usuarios comunes sin roles asignados entren al backend.
        return $this->roles()->exists();
    }
}