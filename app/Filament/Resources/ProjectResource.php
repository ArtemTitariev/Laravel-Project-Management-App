<?php

namespace App\Filament\Resources;

use Filament\Forms;
use App\Models\User;
use Filament\Tables;
use App\Models\Project;
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

class ProjectResource extends Resource
{
    protected static ?string $model = Project::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Fieldset::make('Details')
                    ->schema([
                        TextInput::make('name')->required(),

                        // Select::make('pm_id')
                        //     ->label('Project manager')
                        //     ->options(User::all()->mapWithKeys(function ($user) {
                        //         return [$user->id => $user->name . ' ' . $user->second_name];
                        //     }))
                        //     ->searchable()
                        //     ->required(),

                        Select::make('pm_id')
                            ->label('Project manager')
                            ->options(User::whereHas('userRole', function ($query) {
                                $query->where('name', 'Project manager');
                            })->get()->mapWithKeys(function ($user) {
                                return [$user->id => $user->name . ' ' . $user->second_name];
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
                            ->requiredIf('status_id', '1'),
                        // ->requiredIf('status_id', function ($record) {
                        //     return $record->status->name === 'Finished';
                        // }),
                        //->requiredIf('status.name', 'Finished'),
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

                TextColumn::make('pm_id')
                    ->label('Project Manager')
                    ->formatStateUsing(function ($state, Project $project) {
                        return $project->pm->name . ' ' .
                            $project->pm->second_name;
                    })
                    ->badge()
                    ->color(function (string $state): string {
                        return 'success';
                    }),

                TextColumn::make('category_id')
                    ->label('Category')
                    ->formatStateUsing(function ($state, Project $project) {
                        return $project->category->name;
                    })
                    ->badge(),

                TextColumn::make('status_id')
                    ->label('Status')
                    ->formatStateUsing(function ($state, Project $project) {
                        return $project->status->name;
                    })
                    ->badge()
                    ->color(function (string $state): string {
                        return 'info';
                    }),

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
            'index' => Pages\ListProjects::route('/'),
            'create' => Pages\CreateProject::route('/create'),
            'edit' => Pages\EditProject::route('/{record}/edit'),
        ];
    }
}
