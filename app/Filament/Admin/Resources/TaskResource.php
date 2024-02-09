<?php

namespace App\Filament\Admin\Resources;

use Filament\Tables;
use Filament\Forms\Form;
use App\Models\TaskStatus;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use App\Filament\Admin\Resources\TaskResource\Pages;
use App\Filament\Admin\Resources\TaskResource\RelationManagers\StatusRelationManager;
use App\Filament\Admin\Resources\TaskResource\RelationManagers\CategoryRelationManager;

class TaskResource extends Resource
{
    protected static ?string $model = \App\Models\Task::class;

    protected static ?string $navigationGroup = 'Task Management';
    protected static ?string $navigationIcon = 'heroicon-o-calendar-days';
    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                \Filament\Forms\Components\Fieldset::make('Name and Description')
                    ->schema([
                        \Filament\Forms\Components\TextInput::make('name')
                            ->string()
                            ->maxLength(255)
                            ->required(),

                        \Filament\Forms\Components\Textarea::make('description')
                            ->rows(10)
                            ->cols(20)
                            ->required()
                            ->minLength(1)
                            ->maxLength(255),
                    ])->columns(1),

                \Filament\Forms\Components\Fieldset::make('Details')
                    ->schema([
                        \Filament\Forms\Components\Select::make('project_id')
                            ->label('Project')
                            ->options(\App\Models\Project::all()
                                ->pluck('name', 'id'))
                            ->searchable()
                            ->required(),

                        // Only workers and not project managers
                        \Filament\Forms\Components\Select::make('employee_id')
                            ->label('Employee')
                            ->options(\App\Models\User::whereHas(
                                'userRole',
                                function ($query) {
                                    $query->where(
                                        'name',
                                        \App\Models\UserRole::WORKER
                                    );
                                }
                            )->whereHas(
                                'position',
                                function ($query) {
                                    $query->whereNot(
                                        'name',
                                        \App\Models\Position::PROJECT_MANAGER
                                    );
                                }
                            )->get()->mapWithKeys(function ($user) {
                                return [$user->id => $user->name . ' ' .
                                    $user->second_name];
                            }))
                            ->searchable()
                            ->required(),

                        \Filament\Forms\Components\Select::make('category_id')
                            ->label('Category')
                            ->options(\App\Models\TaskCategory::all()
                                ->pluck('name', 'id'))
                            ->searchable()
                            ->required(),

                        \Filament\Forms\Components\Select::make('status_id')
                            ->label('Status')
                            ->options(TaskStatus::all()->pluck('name', 'id'))
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
                                function (
                                    string $attribute,
                                    $value,
                                    \Closure $fail
                                ) use ($get) {
                                    checkDateFieldWhenFinished(
                                        'start date',
                                        $value,
                                        'task status',
                                        $fail,
                                        $get,
                                        \App\Models\TaskStatus::class
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
                                        'task status',
                                        $fail,
                                        $get,
                                        \App\Models\TaskStatus::class
                                    );
                                },
                            ])
                            ->afterOrEqual('start_date')
                            ->required()
                            ->validationMessages([
                                'required_if' => 'The :attribute field is required when task status is ' .
                                    \App\Models\TaskStatus::FINISHED . '.',
                            ]),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                \Filament\Tables\Columns\TextColumn::make('name')
                    ->label('Name and description')
                    ->description(fn (\App\Models\Task $record): string => $record->description)
                    ->sortable()->searchable()
                    ->limit(40)
                    ->wrap(),

                \Filament\Tables\Columns\TextColumn::make('project.name')
                    ->label('Project')
                    ->badge()
                    ->color(function (string $state): string {
                        return 'info';
                    })
                    ->sortable(),

                \Filament\Tables\Columns\TextColumn::make('category.name')
                    ->label('Category')
                    ->badge()
                    ->sortable(),

                \Filament\Tables\Columns\TextColumn::make('employee.full_name')
                    ->label('Employee')
                    ->listWithLineBreaks()
                    ->badge()
                    ->color(function (string $state): string {
                        return 'success';
                    })
                    ->sortable()
                    ->searchable(),

                \Filament\Tables\Columns\TextColumn::make('status.name')
                    ->label('Status')
                    ->badge()
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
                \Filament\Tables\Filters\SelectFilter::make('project_id')
                    ->label('Project')
                    ->relationship('project', 'name'),
                \Filament\Tables\Filters\SelectFilter::make('category_id')
                    ->label('Category')
                    ->relationship('category', 'name'),

                \Filament\Tables\Filters\SelectFilter::make('employee_id')
                    ->label('Employee')
                    ->relationship('employee', 'full_name'),

                \Filament\Tables\Filters\SelectFilter::make('status_id')
                    ->label('Status')
                    ->relationship('status', 'name')
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
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTasks::route('/'),
            'create' => Pages\CreateTask::route('/create'),
            'view' => Pages\ViewTask::route('/{record}'),
            'edit' => Pages\EditTask::route('/{record}/edit'),
        ];
    }
}
