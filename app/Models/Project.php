<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Project extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'pm_id', 'category_id', 'status_id',
        'start_date', 'finish_date'
    ];

    protected $casts = [
        'pm_id' => 'integer',
        'category_id' => 'integer',
        'status_id' => 'integer',
        'start_date' => 'datetime',
        'finish_date' => 'datetime',
    ];

    //project manager
    public function pm(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(ProjectCategory::class);
    }

    public function status(): BelongsTo
    {
        return $this->belongsTo(ProjectStatus::class);
    }

    public function teams(): BelongsToMany
    {
        return $this->belongsToMany(Team::class, 'team_projects');
    }

    public function tasks(): HasMany
    {
        return $this->hasMany(Task::class);
    }
}
