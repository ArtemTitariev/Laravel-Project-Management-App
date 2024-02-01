<?php

namespace App\Filament\Admin\Resources\ProjectStatusResource\Pages;

use App\Filament\Admin\Resources\ProjectStatusResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditProjectStatus extends EditRecord
{
    protected static string $resource = ProjectStatusResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
