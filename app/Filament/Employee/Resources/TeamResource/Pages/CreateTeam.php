<?php

namespace App\Filament\Employee\Resources\TeamResource\Pages;

use App\Filament\Employee\Resources\TeamResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateTeam extends CreateRecord
{
    protected static string $resource = TeamResource::class;
}
