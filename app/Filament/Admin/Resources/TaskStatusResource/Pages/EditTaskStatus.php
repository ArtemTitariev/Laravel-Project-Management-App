<?php

namespace App\Filament\Admin\Resources\TaskStatusResource\Pages;

use App\Filament\Admin\Resources\TaskStatusResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditTaskStatus extends EditRecord
{
    protected static string $resource = TaskStatusResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
