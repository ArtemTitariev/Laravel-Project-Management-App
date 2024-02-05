<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ProjectStatus extends Model
{
    use HasFactory;

    const FINISHED = 'Finished';
    const IN_PROGRESS = 'In-Progress';
    
    protected $fillable = [
        'name',
    ];

    public function projects(): HasMany
    {
        return $this->hasMany(Project::class, 'status_id');
    }
}
