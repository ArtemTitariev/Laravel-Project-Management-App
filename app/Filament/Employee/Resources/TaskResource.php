<?php

namespace App\Filament\Employee\Resources;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use App\Filament\Employee\Resources\TaskResource\Pages;

class TaskResource extends Resource
{
    protected static ?string $model = \App\Models\Task::class;

    protected static ?string $navigationGroup = 'Tasks';
    protected static ?string $navigationIcon = 'heroicon-o-calendar-days';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Fieldset::make('Name and Description')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->string()
                            ->maxLength(255)
                            ->disabled(),

                        Forms\Components\Textarea::make('description')
                            ->rows(10)
                            ->cols(20)
                            ->required()
                            ->minLength(1)
                            ->maxLength(255),
                    ])->columns(1),

                Forms\Components\Fieldset::make('Details')
                    ->schema([
                        Forms\Components\Select::make('project_id')
                            ->label('Project')
                            ->options(
                                auth()->user()->projects->pluck('name', 'id')
                            )
                            ->searchable()
                            ->disabled(),

                        Forms\Components\Select::make('employee_id')
                            ->label('Employee')
                            ->options([
                                auth()->user()->id => auth()->user()->full_name
                            ])
                            ->default(auth()->user()->id)
                            ->disabled(),

                        Forms\Components\Select::make('category_id')
                            ->label('Category')
                            ->options(\App\Models\TaskCategory::all()
                                ->pluck('name', 'id'))
                            ->searchable()
                            ->disabled(),

                        Forms\Components\Select::make('status_id')
                            ->label('Status')
                            ->options(\App\Models\TaskStatus::all()
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
            ->query(
                \App\Models\Task::where('employee_id', auth()->user()->id)
            )
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Name and Description')
                    ->description(
                        fn (\App\Models\Task $record): string =>
                        $record->description
                    )
                    ->sortable()->searchable()
                    ->limit(40)
                    ->wrap(),

                Tables\Columns\TextColumn::make('project.name')
                    ->label('Project')
                    ->badge()
                    ->color(function (string $state): string {
                        return 'info';
                    })
                    ->sortable(),

                Tables\Columns\TextColumn::make('category.name')
                    ->label('Category')
                    ->badge()
                    ->sortable(),

                Tables\Columns\TextColumn::make('employee.full_name')
                    ->label('Employee')
                    ->listWithLineBreaks()
                    ->badge()
                    ->color(function (string $state): string {
                        return 'success';
                    })
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('status.name')
                    ->label('Status')
                    ->badge()
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
                Tables\Filters\SelectFilter::make('project_id')
                    ->label('Project')
                    ->relationship('project', 'name'),
                Tables\Filters\SelectFilter::make('category_id')
                    ->label('Category')
                    ->relationship('category', 'name'),

                Tables\Filters\SelectFilter::make('status_id')
                    ->label('Status')
                    ->relationship('status', 'name')
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                //
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTasks::route('/'),
            'view' => Pages\ViewTask::route('/{record}'),
            'edit' => Pages\EditTask::route('/{record}/edit'),
        ];
    }
}
