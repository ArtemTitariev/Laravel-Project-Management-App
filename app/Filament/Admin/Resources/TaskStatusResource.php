<?php

namespace App\Filament\Admin\Resources;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use App\Filament\Admin\Resources\TaskStatusResource\Pages;

class TaskStatusResource extends Resource
{
    protected static ?string $model = \App\Models\TaskStatus::class;

    protected static ?string $navigationGroup = 'Task Management';
    protected static ?string $navigationLabel = 'Statuses';
    protected static ?string $navigationIcon = 'heroicon-o-check-circle';
    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                \Filament\Forms\Components\TextInput::make('name')
                    ->string()
                    ->maxLength(255)
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                \Filament\Tables\Columns\TextColumn::make('name')
                    ->sortable()->searchable()
                    ->wrap(),
                \Filament\Tables\Columns\TextColumn::make('created_at')
                    ->date()
                    ->sortable()->searchable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\DeleteAction::make()
                    ->action(function ($data, $record) {
                        if ($record->tasks->isNotEmpty()) {
                            sendErrorNotification(
                                'Task Status',
                                'Tasks',
                                $record->name
                            );
                        }
                    }),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->action(function ($data, $records) {
                            foreach ($records as $record) {
                                if ($record->tasks->isNotEmpty()) {
                                    sendErrorNotification(
                                        'Task Status',
                                        'Tasks',
                                        $record->name
                                    );
                                }
                            }
                        }),
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
            'index' => Pages\ListTaskStatuses::route('/'),
            'create' => Pages\CreateTaskStatus::route('/create'),
            'edit' => Pages\EditTaskStatus::route('/{record}/edit'),
        ];
    }
}
