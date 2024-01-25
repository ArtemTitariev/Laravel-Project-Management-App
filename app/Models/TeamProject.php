<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TeamProject extends Model
{
    use HasFactory;

    protected $fillable = [
        'team_id', 'project_id'
    ];

    protected $casts = [
        'team_id' => 'integer',
        'project_id' => 'integer',
    ];
}
