<?php

namespace App\Filament\Admin\Resources;

use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use App\Filament\Admin\Resources\UserResource\Pages;
use App\Filament\Admin\Resources\UserResource\RelationManagers\PositionRelationManager;
use App\Filament\Admin\Resources\UserResource\RelationManagers\UserRoleRelationManager;

class UserResource extends Resource
{
    protected static ?string $model = \App\Models\User::class;
    protected static ?string $navigationGroup = 'User Management';
    protected static ?string $navigationIcon = 'heroicon-o-users';
    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                \Filament\Forms\Components\Fieldset::make('Personal info')
                    ->schema([
                        \Filament\Forms\Components\TextInput::make('name')
                            ->label('First name')
                            ->string()
                            ->maxLength(255)
                            ->required(),

                        \Filament\Forms\Components\TextInput::make('second_name')
                            ->label('Second name')
                            ->string()
                            ->maxLength(255)
                            ->required(),

                        \Filament\Forms\Components\TextInput::make('email')
                            ->email()
                            ->dehydrated(fn ($state) => filled($state))
                            ->required(fn (string $context): bool => $context === 'create')
                            ->unique(ignoreRecord: true),

                        myPasswordField(), // helper
                    ]),
                \Filament\Forms\Components\Fieldset::make('Position and role')
                    ->schema([
                        \Filament\Forms\Components\Select::make('position_id')
                            ->label('Position')
                            ->options(\App\Models\Position::all()
                                ->pluck('name', 'id'))
                            ->searchable()
                            ->required(),
                        \Filament\Forms\Components\Select::make('user_role_id')
                            ->label('Role')
                            ->options(\App\Models\UserRole::all()
                                ->pluck('name', 'id'))
                            ->searchable()
                            ->required(),
                    ]),

                \Filament\Forms\Components\SpatieMediaLibraryFileUpload::make('avatar'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                \Filament\Tables\Columns\TextColumn::make('name')
                    ->label('Full Name')
                    ->formatStateUsing(function ($state, \App\Models\User $user) {
                        return $user->name . ' ' . $user->second_name;
                    })->sortable()->searchable(),

                \Filament\Tables\Columns\TextColumn::make('email')
                    ->copyable()
                    ->copyMessage('Email address copied')
                    ->sortable()->searchable(),

                \Filament\Tables\Columns\TextColumn::make('position.name')
                    ->label('Position')
                    ->badge()
                    ->sortable(),

                \Filament\Tables\Columns\TextColumn::make('userRole.name')
                    ->label('Role')
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

                \Filament\Tables\Columns\SpatieMediaLibraryImageColumn::make('avatar')
                    ->defaultImageUrl('/storage/no-image-available.jpg')
                    ->circular(),

                \Filament\Tables\Columns\TextColumn::make('created_at')
                    ->label('Register at')
                    ->date()
                    ->sortable()
                    ->searchable(),
            ])
            ->filters([
                \Filament\Tables\Filters\SelectFilter::make('position_id')
                    ->label('Position')
                    ->relationship('position', 'name'),

                \Filament\Tables\Filters\SelectFilter::make('user_role_id')
                    ->label('Role')
                    ->relationship('userRole', 'name'),
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
            ])
            ->emptyStateActions([
                Tables\Actions\CreateAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            PositionRelationManager::class,
            UserRoleRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'view' => Pages\ViewUser::route('/{record}'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}
