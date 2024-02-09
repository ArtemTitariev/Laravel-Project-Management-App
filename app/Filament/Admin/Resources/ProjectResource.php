<?php

namespace App\Filament\Admin\Resources;

use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use App\Filament\Admin\Resources\ProjectResource\Pages;
use App\Filament\Admin\Resources\ProjectResource\RelationManagers\TeamsRelationManager;
use App\Filament\Admin\Resources\ProjectResource\RelationManagers\StatusRelationManager;
use App\Filament\Admin\Resources\ProjectResource\RelationManagers\CategoryRelationManager;

class ProjectResource extends Resource
{
    protected static ?string $model = \App\Models\Project::class;

    protected static ?string $navigationGroup = 'Project Management';
    protected static ?string $navigationIcon = 'heroicon-o-briefcase';
    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                \Filament\Forms\Components\Fieldset::make('Details')
                    ->schema([
                        \Filament\Forms\Components\TextInput::make('name')
                            ->string()
                            ->maxLength(255)
                            ->required(),

                        \Filament\Forms\Components\Select::make('pm_id')
                            ->label('Project manager')
                            ->options(
                                \App\Models\User::whereHas(
                                    'position',
                                    function ($query) {
                                        $query->where(
                                            'name',
                                            \App\Models\Position::PROJECT_MANAGER
                                        );
                                    }
                                )->get()->mapWithKeys(function ($user) {
                                    return [$user->id => $user->full_name];
                                })
                            )
                            ->searchable()
                            ->required(),

                        \Filament\Forms\Components\Select::make('category_id')
                            ->label('Category')
                            ->options(\App\Models\ProjectCategory::all()
                                ->pluck('name', 'id'))
                            ->searchable()
                            ->required(),

                        \Filament\Forms\Components\Select::make('status_id')
                            ->label('Status')
                            ->options(\App\Models\ProjectStatus::all()
                                ->pluck('name', 'id'))
                            ->searchable()
                            ->required(),
                    ]),

                \Filament\Forms\Components\Fieldset::make('Dates')
                    ->schema([
                        \Filament\Forms\Components\DatePicker::make('start_date')
                            ->format('d.m.Y')
                            ->closeOnDateSelection()
                            ->rules([
                                fn (\Filament\Forms\Get $get): \Closure =>
                                function (string $attribute, $value, \Closure $fail) use ($get) {
                                    checkDateFieldWhenFinished(
                                        'start date',
                                        $value,
                                        'project status',
                                        $fail,
                                        $get,
                                        \App\Models\ProjectStatus::class
                                    );
                                },
                            ])
                            ->required(),

                        \Filament\Forms\Components\DatePicker::make('finish_date')
                            ->format('d.m.Y')
                            ->closeOnDateSelection()
                            ->rules([
                                fn (\Filament\Forms\Get $get): \Closure =>
                                function (
                                    string $attribute,
                                    $value,
                                    \Closure $fail
                                ) use ($get) {
                                    checkDateFieldWhenFinished(
                                        'finish date',
                                        $value,
                                        'project status',
                                        $fail,
                                        $get,
                                        \App\Models\ProjectStatus::class
                                    );
                                },
                            ])
                            ->afterOrEqual('start_date')
                            ->required()
                            ->validationMessages([
                                'required_if' => 'The :attribute field is required when project status is ' .
                                    \App\Models\ProjectStatus::FINISHED . '.',
                            ]),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                \Filament\Tables\Columns\TextColumn::make('name')
                    ->sortable()->searchable()
                    ->wrap(),

                \Filament\Tables\Columns\TextColumn::make('pm.full_name')
                    ->label('Project Manager')
                    ->badge()
                    ->color(function (string $state): string {
                        return 'success';
                    })
                    ->sortable(),

                \Filament\Tables\Columns\TextColumn::make('teams.name')
                    ->label('Teams')
                    ->listWithLineBreaks()
                    ->bulleted()
                    ->limitList(3)
                    ->expandableLimitedList()
                    ->searchable(),

                \Filament\Tables\Columns\TextColumn::make('category.name')
                    ->label('Category')
                    ->badge()
                    ->sortable(),

                \Filament\Tables\Columns\TextColumn::make('status.name')
                    ->label('Status')
                    ->badge()
                    ->color(function (string $state): string {
                        return 'info';
                    })
                    ->sortable(),

                \Filament\Tables\Columns\TextColumn::make('start_date')
                    ->label('Start date')
                    ->date()
                    ->sortable()->searchable(),
                \Filament\Tables\Columns\TextColumn::make('finish_date')
                    ->label('Finish date')
                    ->date()
                    ->sortable()->searchable(),
            ])
            ->filters([
                \Filament\Tables\Filters\SelectFilter::make('category_id')
                    ->label('Category')
                    ->relationship('category', 'name'),
                \Filament\Tables\Filters\SelectFilter::make('status_id')
                    ->label('Status')
                    ->relationship('status', 'name'),

                \Filament\Tables\Filters\SelectFilter::make('pm_id')
                    ->label('Project manager')
                    ->options(function () {
                        return \App\Models\User::whereHas(
                            'position',
                            function ($query) {
                                $query->where(
                                    'name',
                                    \App\Models\Position::PROJECT_MANAGER
                                );
                            }
                        )->pluck('full_name', 'id');
                    }),

                \Filament\Tables\Filters\SelectFilter::make('teams')
                    ->label('Teams')
                    ->relationship('teams', 'name')
                    ->multiple()
                    ->preload(),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
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
            'view' => Pages\ViewProject::route('/{record}'),
            'edit' => Pages\EditProject::route('/{record}/edit'),
        ];
    }
}
