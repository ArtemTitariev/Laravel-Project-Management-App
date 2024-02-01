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

    public function isProjectManager()
    {
        return $this->name === self::PROJECT_MANAGER;
    }

    public function isDeveloper()
    {
        return $this->name === self::DEVELOPER;
    }

    public function isDesigner()
    {
        return $this->name === self::DESIGNER;
    }

    public function isTester()
    {
        return $this->name === self::TESTER;
    }

    protected $fillable = [
        'name',
    ];

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }
}
