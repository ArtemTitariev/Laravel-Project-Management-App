<?php

namespace App\Filament\Employee\Resources;

use Filament\Forms;
use App\Models\Task;
use Filament\Tables;
use App\Models\Project;
use Filament\Forms\Form;
use App\Models\TaskStatus;
use Filament\Tables\Table;
use App\Models\TaskCategory;
use Filament\Resources\Resource;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Employee\Resources\TaskResource\Pages;
use App\Filament\Employee\Resources\TaskResource\RelationManagers;

class TaskResource extends Resource
{
    protected static ?string $model = Task::class;

    protected static ?string $navigationGroup = 'Tasks';
    protected static ?string $navigationIcon = 'heroicon-o-calendar-days';

    // public static function query(): Builder
    // {
    //     return Task::where('user_id', auth()->user()->id);
    // }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Fieldset::make('Name and Description')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->string()
                            ->maxLength(255)
                            ->required(),

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
                            ->required(),

                        Forms\Components\Select::make('employee_id')
                            ->label('Employee')
                            ->options([
                                auth()->user()->id => auth()->user()->full_name
                            ])
                            ->default(auth()->user()->id)
                            ->required(),

                        Forms\Components\Select::make('category_id')
                            ->label('Category')
                            ->options(TaskCategory::all()->pluck('name', 'id'))
                            ->searchable()
                            ->required(),

                        Forms\Components\Select::make('status_id')
                            ->label('Status')
                            ->options(TaskStatus::all()->pluck('name', 'id'))
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
                            ->required(),
                        //->requiredIf('status_id', '1'),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->query(
                Task::where('employee_id', auth()->user()->id)
            )
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Name and description')
                    ->description(fn (Task $record): string => $record->description)
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
                // Tables\Actions\BulkActionGroup::make([
                //     Tables\Actions\DeleteBulkAction::make(),
                // ]),
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
            'create' => Pages\CreateTask::route('/create'),
            'view' => Pages\ViewTask::route('/{record}'),
            'edit' => Pages\EditTask::route('/{record}/edit'),
        ];
    }
}
