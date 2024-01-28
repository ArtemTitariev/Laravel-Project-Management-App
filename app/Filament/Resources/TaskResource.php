<?php

namespace App\Filament\Resources;

use Filament\Forms;
use App\Models\Task;
use App\Models\User;
use Filament\Tables;
use App\Models\Project;
use Filament\Forms\Form;
use App\Models\TaskStatus;
use Filament\Tables\Table;
use App\Models\TaskCategory;
use Filament\Resources\Resource;
use Filament\Tables\Filters\Filter;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\TaskResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\TaskResource\RelationManagers;

class TaskResource extends Resource
{
    protected static ?string $model = Task::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Fieldset::make('Name and description')
                    ->schema([
                        TextInput::make('name')->required(),

                        Textarea::make('description')
                            ->rows(10)
                            ->cols(20)
                            ->required()
                            ->minLength(1)
                            ->maxLength(255),
                    ])->columns(1),

                Fieldset::make('Details')
                    ->schema([
                        Select::make('project_id')
                            ->label('Project')
                            ->options(Project::all()->pluck('name', 'id'))
                            ->searchable()
                            ->required(),

                        Select::make('employee_id')
                            ->label('Employee')
                            ->options(User::all()->mapWithKeys(function ($user) {
                                return [$user->id => $user->name . ' ' . $user->second_name];
                            }))
                            ->searchable()
                            ->required(),

                        Select::make('category_id')
                            ->label('Category')
                            ->options(TaskCategory::all()->pluck('name', 'id'))
                            ->searchable()
                            ->required(),

                        Select::make('status_id')
                            ->label('Status')
                            ->options(TaskStatus::all()->pluck('name', 'id'))
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
                            ->requiredIf('status_id', '1'),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Name and description')
                    ->description(fn (Task $record): string => $record->description)
                    ->sortable()->searchable()
                    ->limit(40)
                    ->wrap(),

                TextColumn::make('project.name')
                    ->label('Project')
                    ->badge()
                    ->color(function (string $state): string {
                        return 'info';
                    })
                    ->sortable(),

                TextColumn::make('category.name')
                    ->label('Category')
                    ->badge()
                    ->sortable(),

                TextColumn::make('employee.full_name')
                    ->label('Employee')
                    ->listWithLineBreaks()
                    ->badge()
                    ->color(function (string $state): string {
                        return 'success';
                    })
                    ->sortable()
                    ->searchable(),

                TextColumn::make('status.name')
                    ->label('Status')
                    ->badge()
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
                SelectFilter::make('project_id')
                    ->label('Project')
                    ->relationship('project', 'name'),
                SelectFilter::make('category_id')
                    ->label('Category')
                    ->relationship('category', 'name'),

                SelectFilter::make('employee_id')
                    ->label('Employee')
                    ->relationship('employee', 'full_name'),


                // Filter::make('employee_id')
                //     ->form([
                //         Select::make('employee_id')
                //             ->label('Employee')
                //             ->options(User::all()->mapWithKeys(function ($user) {
                //                 return [$user->id => $user->name . ' ' . $user->second_name];
                //             }))
                //             ->searchable()
                //     ])
                //     ->query(function (Builder $query, array $data): Builder {
                //         return $query->where('employee_id', $data['employee_id'] ?? null);
                //     }),


                SelectFilter::make('status_id')
                    ->label('Status')
                    ->relationship('status', 'name')
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
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
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTasks::route('/'),
            'create' => Pages\CreateTask::route('/create'),
            'edit' => Pages\EditTask::route('/{record}/edit'),
        ];
    }
}
