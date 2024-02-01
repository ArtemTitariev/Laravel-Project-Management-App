<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class UserRole extends Model
{
    use HasFactory;

    const ADMIN = 'Admin';
    const WORKER = 'Worker';

    public function isAdmin()
    {
        return $this->name === self::ADMIN;
    }

    public function isWorker()
    {
        return $this->name === self::WORKER;
    }


    protected $fillable = [
        'name',
    ];

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }
}
