<?php

namespace App\Filament\Admin\Resources\TaskStatusResource\Pages;

use App\Filament\Admin\Resources\TaskStatusResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateTaskStatus extends CreateRecord
{
    protected static string $resource = TaskStatusResource::class;
}
