<?php

namespace App\Filament\Admin\Resources\ProjectStatusResource\Pages;

use App\Filament\Admin\Resources\ProjectStatusResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListProjectStatuses extends ListRecords
{
    protected static string $resource = ProjectStatusResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
