<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Task extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'description',
        'project_id', 'category_id',
        'employee_id', 'status_id',
        'start_date', 'finish_date'
    ];

    protected $casts = [
        'project_id' => 'integer',
        'category_id' => 'integer',
        'status_id' => 'integer',
        'employee_id' => 'integer',
        'start_date' => 'datetime',
        'finish_date' => 'datetime',
    ];

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function employee(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(TaskCategory::class);
    }

    public function status(): BelongsTo
    {
        return $this->belongsTo(TaskStatus::class);
    }
}
