<?php

namespace App\Models;

use App\Enum\PositionEnum;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Position extends Model
{
    use HasFactory;

    const PROJECT_MANAGER = 'Project manager';
    const DEVELOPER = 'Developer';
    const DESIGNER = 'Designer';
    const TESTER = 'Tester';


    protected $fillable = [
        'name',
    ];

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }
}
