<?php

namespace App\Filament\Resources;

use App\Enum\PositionEnum;
use Filament\Forms;
use App\Models\User;
use Filament\Tables;
use App\Models\Project;
use Filament\Forms\Get;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\ProjectStatus;
use App\Models\ProjectCategory;
use Filament\Resources\Resource;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Fieldset;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\ProjectResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\ProjectResource\RelationManagers;
use App\Filament\Resources\ProjectResource\RelationManagers\TeamsRelationManager;
use App\Filament\Resources\ProjectResource\RelationManagers\StatusRelationManager;
use App\Filament\Resources\ProjectResource\RelationManagers\CategoryRelationManager;

class ProjectResource extends Resource
{
    protected static ?string $model = Project::class;

    protected static ?string $navigationGroup = 'Project Management';
    protected static ?string $navigationIcon = 'heroicon-o-briefcase';
    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Fieldset::make('Details')
                    ->schema([
                        TextInput::make('name')
                            ->string()
                            ->maxLength(255)
                            ->required(),

                        Select::make('pm_id')
                            ->label('Project manager')
                            ->options(User::whereHas('userRole', function ($query) {
                                $query->where('name', \App\Models\Position::PROJECT_MANAGER);
                            })->get()->mapWithKeys(function ($user) {
                                return [$user->id => $user->full_name];
                            }))
                            ->searchable()
                            ->required(),

                        Select::make('category_id')
                            ->label('Category')
                            ->options(ProjectCategory::all()->pluck('name', 'id'))
                            ->searchable()
                            ->required(),

                        Select::make('status_id')
                            ->label('Status')
                            ->options(ProjectStatus::all()->pluck('name', 'id'))
                            ->searchable()
                            ->required(),
                    ]),

                Fieldset::make('Dates')
                    ->schema([
                        DatePicker::make('start_date')
                            ->format('d.m.Y')
                            ->closeOnDateSelection()
                            ->required(),

                        DatePicker::make('finish_date')
                            ->format('d.m.Y')
                            ->closeOnDateSelection()
                            ->afterOrEqual('start_date')
                            ->requiredIf('status_id', function ($record) {
                                return $record->status_id === ProjectStatus::where('name', 'Finished')->first()->id;
                            })
                            ->validationMessages([
                                'required_if' => 'The :attribute field is required when project status is Finished.',
                            ]),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->sortable()->searchable()
                    ->wrap(),

                TextColumn::make('pm.full_name')
                    ->label('Project Manager')
                    ->badge()
                    ->color(function (string $state): string {
                        return 'success';
                    })
                    ->sortable(),

                TextColumn::make('teams.name')
                    ->label('Teams')
                    ->listWithLineBreaks()
                    ->bulleted()
                    ->limitList(3)
                    ->expandableLimitedList()
                    ->searchable(),

                TextColumn::make('category.name')
                    ->label('Category')
                    ->badge()
                    ->sortable(),

                TextColumn::make('status.name')
                    ->label('Status')
                    ->badge()
                    ->color(function (string $state): string {
                        return 'info';
                    })
                    ->sortable(),

                TextColumn::make('start_date')
                    ->label('Start date')
                    ->date()
                    ->sortable()->searchable(),
                TextColumn::make('finish_date')
                    ->label('Finish date')
                    ->date()
                    ->sortable()->searchable(),
            ])
            ->filters([
                SelectFilter::make('category_id')
                    ->label('Category')
                    ->relationship('category', 'name'),
                SelectFilter::make('status_id')
                    ->label('Status')
                    ->relationship('status', 'name'),

                // виводить всіх користувачів, а не тільки PM-ів
                // SelectFilter::make('pm_id')
                //     ->label('Project manager')
                //     ->relationship('pm', 'full_name'),


                SelectFilter::make('teams')
                    ->label('Teams')
                    ->relationship('teams', 'name')
                    ->multiple(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            CategoryRelationManager::class,
            StatusRelationManager::class,
            TeamsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListProjects::route('/'),
            'create' => Pages\CreateProject::route('/create'),
            'edit' => Pages\EditProject::route('/{record}/edit'),
        ];
    }
}
