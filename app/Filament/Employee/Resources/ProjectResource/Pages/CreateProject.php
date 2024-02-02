<?php

namespace App\Filament\Employee\Resources\ProjectResource\Pages;

use App\Filament\Employee\Resources\ProjectResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateProject extends CreateRecord
{
    protected static string $resource = ProjectResource::class;
}
