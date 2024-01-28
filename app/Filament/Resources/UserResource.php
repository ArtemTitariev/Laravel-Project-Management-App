<?php

namespace App\Filament\Resources;

use Filament\Forms;
use App\Models\User;
use Filament\Tables;
use App\Models\Position;
use App\Models\UserRole;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Illuminate\Support\Facades\Hash;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Fieldset;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\UserResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\UserResource\RelationManagers;
use App\Filament\Resources\UserResource\RelationManagers\PositionRelationManager;
use App\Filament\Resources\UserResource\RelationManagers\UserRoleRelationManager;
use Filament\Tables\Columns\SpatieMediaLibraryImageColumn;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Fieldset::make('Personal info')
                    ->schema([
                        TextInput::make('name')
                            ->label('First name')
                            ->required(),
                        TextInput::make('second_name')
                            ->label('Second name')
                            ->required(),
                        TextInput::make('email')->required(),

                        TextInput::make('password')
                            ->password()
                            ->dehydrateStateUsing(fn ($state) => Hash::make($state))
                            ->dehydrated(fn ($state) => filled($state))
                            ->required(fn (string $context): bool => $context === 'create')
                    ]),
                Fieldset::make('Position and role')
                    ->schema([
                        Select::make('position_id')
                            ->label('Position')
                            ->options(Position::all()->pluck('name', 'id'))
                            ->searchable()
                            ->required(),
                        Select::make('user_role_id')
                            ->label('Role')
                            ->options(UserRole::all()->pluck('name', 'id'))
                            ->searchable()
                            ->required(),
                    ]),

                SpatieMediaLibraryFileUpload::make('avatar'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Full Name')
                    ->formatStateUsing(function ($state, User $user) {
                        return $user->name . ' ' . $user->second_name;
                    })->sortable()->searchable(),

                TextColumn::make('email')
                    ->copyable()
                    ->copyMessage('Email address copied')
                    ->sortable()->searchable(),

                TextColumn::make('position.name')
                    ->label('Position')
                    ->badge()
                    ->sortable(),

                TextColumn::make('userRole.name')
                    ->label('Role')
                    ->badge()
                    ->color(function (string $state): string {
                        return 'success';
                    })
                    ->sortable(),

                TextColumn::make('teams.name')
                    ->label('Teams')
                    ->listWithLineBreaks()
                    ->bulleted()
                    ->limitList(3)
                    ->expandableLimitedList()
                    ->searchable(),

                SpatieMediaLibraryImageColumn::make('avatar')->circular(),

                TextColumn::make('created_at')
                    ->label('Register at')
                    ->date()
                    ->sortable()
                    ->searchable(),
            ])
            ->filters([
                SelectFilter::make('position_id')
                    ->label('Position')
                    ->relationship('position', 'name'),

                SelectFilter::make('user_role_id')
                    ->label('Role')
                    ->relationship('userRole', 'name'),
            ])
            ->actions([
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
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}
