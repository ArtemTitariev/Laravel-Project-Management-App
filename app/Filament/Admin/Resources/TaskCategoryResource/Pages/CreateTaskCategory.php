<?php

namespace App\Filament\Admin\Resources\TaskCategoryResource\Pages;

use App\Filament\Admin\Resources\TaskCategoryResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateTaskCategory extends CreateRecord
{
    protected static string $resource = TaskCategoryResource::class;
}
