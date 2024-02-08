<?php

namespace App\Filament\Admin\Resources;

use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use App\Filament\Admin\Resources\UserRoleResource\Pages;

class UserRoleResource extends Resource
{
    protected static ?string $model = \App\Models\UserRole::class;

    protected static ?string $navigationGroup = 'User Management';
    protected static ?string $navigationLabel = 'Roles';
    protected static ?string $navigationIcon = 'heroicon-o-ellipsis-horizontal-circle';
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
                Tables\Actions\DeleteAction::make()
                    ->action(function ($data, $record) {
                        if ($record->users->isNotEmpty()) {
                            sendErrorNotification(
                                'User Role',
                                'Users',
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
                                if ($record->users->isNotEmpty()) {
                                    sendErrorNotification(
                                        'User Role',
                                        'Users',
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
            'index' => Pages\ListUserRoles::route('/'),
            'create' => Pages\CreateUserRole::route('/create'),
            'edit' => Pages\EditUserRole::route('/{record}/edit'),
        ];
    }
}
