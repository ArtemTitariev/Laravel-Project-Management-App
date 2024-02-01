<?php

namespace App\Filament\Admin\Resources\TaskCategoryResource\Pages;

use App\Filament\Admin\Resources\TaskCategoryResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditTaskCategory extends EditRecord
{
    protected static string $resource = TaskCategoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
