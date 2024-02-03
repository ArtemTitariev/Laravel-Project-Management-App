<?php

namespace App\Filament\Admin\Resources;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use App\Filament\Admin\Resources\TaskCategoryResource\Pages;

class TaskCategoryResource extends Resource
{
    protected static ?string $model = \App\Models\TaskCategory::class;

    protected static ?string $navigationGroup = 'Task Management';
    protected static ?string $navigationLabel = 'Categories';
    protected static ?string $navigationIcon = 'heroicon-o-squares-2x2';
    protected static ?int $navigationSort = 3;

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
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTaskCategories::route('/'),
            'create' => Pages\CreateTaskCategory::route('/create'),
            'edit' => Pages\EditTaskCategory::route('/{record}/edit'),
        ];
    }
}
