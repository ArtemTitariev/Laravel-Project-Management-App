<?php

namespace App\Models;

use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Laravel\Sanctum\HasApiTokens;
use Spatie\MediaLibrary\HasMedia;
use Illuminate\Notifications\Notifiable;
use Spatie\MediaLibrary\InteractsWithMedia;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class User extends Authenticatable implements FilamentUser, HasMedia
{
    use InteractsWithMedia, HasApiTokens, HasFactory, Notifiable;


    public function canAccessPanel(Panel $panel): bool
    {
        if ($panel->getId() === 'admin') {
            return $this->userRole->isAdmin() ||
                $this->position->isProjectManager();
        } elseif ($panel->getId() === 'employee') {
            return $this->userRole->isWorker() &&
                !$this->position->isProjectManager();
        }
        return false;
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name', 'second_name', 'full_name',
        'email', 'password',
        'avatar',
        'position_id', 'user_role_id',
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
        'password' => 'hashed',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'position_id' => 'integer',
        'user_role_id' => 'integer',
    ];

    public function position(): BelongsTo
    {
        return $this->belongsTo(Position::class);
    }

    public function tasks(): HasMany
    {
        return $this->hasMany(Task::class, 'employee_id');
    }

    public function projects(): HasManyThrough
    {
        return $this->hasManyThrough(
            Project::class,
            Task::class,
            'employee_id',
            'id',
            'id',
            'project_id'
        );
    }

    public function userRole(): BelongsTo
    {
        return $this->belongsTo(UserRole::class);
    }

    public function teams(): BelongsToMany
    {
        return $this->belongsToMany(Team::class, 'team_members');
    }
}
