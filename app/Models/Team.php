<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Team extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
    ];

    public function members(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'team_members');
    }

    public function projects(): BelongsToMany
    {
        return $this->belongsToMany(Project::class, 'team_projects');
    }

    public static function createWithMembers(array $attributes, array $memberIds): Team
    {
        $team = self::create($attributes);
        $team->members()->attach($memberIds);

        return $team;
    }
}
