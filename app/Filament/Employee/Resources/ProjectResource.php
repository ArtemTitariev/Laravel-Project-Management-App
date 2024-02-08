<?php

namespace App\Filament\Employee\Resources;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use App\Filament\Employee\Resources\ProjectResource\Pages;
use App\Filament\Employee\Resources\ProjectResource\RelationManagers\TeamsRelationManager;

class ProjectResource extends Resource
{
    protected static ?string $model = \App\Models\Project::class;

    protected static ?string $navigationGroup = 'Projects';
    protected static ?string $navigationIcon = 'heroicon-o-briefcase';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Fieldset::make('Details')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->string()
                            ->maxLength(255)
                            ->required(),

                        Forms\Components\Select::make('pm_id')
                            ->label('Project manager')
                            ->options(\App\Models\User::whereHas(
                                'userRole',
                                function ($query) {
                                    $query->where(
                                        'name',
                                        \App\Models\Position::PROJECT_MANAGER
                                    );
                                }
                            )->get()->mapWithKeys(function ($user) {
                                return [$user->id => $user->full_name];
                            }))
                            ->searchable()
                            ->required(),

                        Forms\Components\Select::make('category_id')
                            ->label('Category')
                            ->options(\App\Models\ProjectCategory::all()
                                ->pluck('name', 'id'))
                            ->searchable()
                            ->required(),

                        Forms\Components\Select::make('status_id')
                            ->label('Status')
                            ->options(\App\Models\ProjectStatus::all()
                                ->pluck('name', 'id'))
                            ->searchable()
                            ->required(),
                    ]),

                Forms\Components\Fieldset::make('Dates')
                    ->schema([
                        Forms\Components\DatePicker::make('start_date')
                            ->format('d.m.Y')
                            ->closeOnDateSelection()
                            ->required(),

                        Forms\Components\DatePicker::make('finish_date')
                            ->format('d.m.Y')
                            ->closeOnDateSelection()
                            ->afterOrEqual('start_date')
                            ->requiredIf('status_id', function ($record) {
                                return $record->status_id ===
                                    \App\Models\ProjectStatus::where(
                                        'name',
                                        \App\Models\ProjectStatus::FINISHED
                                    )
                                    ->first()->id;
                            })
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
            ->query(
                \App\Models\Project::whereHas('tasks', function ($query) {
                    $query->where('employee_id', auth()->user()->id);
                })
            )
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->sortable()->searchable()
                    ->wrap(),

                Tables\Columns\TextColumn::make('pm.full_name')
                    ->label('Project Manager')
                    ->badge()
                    ->color(function (string $state): string {
                        return 'success';
                    })
                    ->sortable(),

                Tables\Columns\TextColumn::make('teams.name')
                    ->label('Teams')
                    ->listWithLineBreaks()
                    ->bulleted()
                    ->limitList(3)
                    ->expandableLimitedList()
                    ->searchable(),

                Tables\Columns\TextColumn::make('category.name')
                    ->label('Category')
                    ->badge()
                    ->sortable(),

                Tables\Columns\TextColumn::make('status.name')
                    ->label('Status')
                    ->badge()
                    ->color(function (string $state): string {
                        return 'info';
                    })
                    ->sortable(),

                Tables\Columns\TextColumn::make('start_date')
                    ->label('Start date')
                    ->date()
                    ->sortable()->searchable(),
                Tables\Columns\TextColumn::make('finish_date')
                    ->label('Finish date')
                    ->date()
                    ->sortable()->searchable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
            ])
            ->bulkActions([
                //
            ]);
    }

    public static function getRelations(): array
    {
        return [
            TeamsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListProjects::route('/'),
            'view' => Pages\ViewProject::route('/{record}'),
        ];
    }
}
