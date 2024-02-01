<?php

namespace App\Filament\Admin\Resources\TaskCategoryResource\Pages;

use App\Filament\Admin\Resources\TaskCategoryResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListTaskCategories extends ListRecords
{
    protected static string $resource = TaskCategoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
