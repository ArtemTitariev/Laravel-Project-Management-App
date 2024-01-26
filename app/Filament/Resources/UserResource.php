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
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\UserResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\UserResource\RelationManagers;
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

                TextColumn::make('position_id')
                    ->label('Position')
                    ->formatStateUsing(function ($state, User $user) {
                        return $user->position->name;
                    })
                    ->badge(),

                TextColumn::make('user_role_id')
                    ->label('Role')
                    ->formatStateUsing(function ($state, User $user) {
                        return $user->userRole->name;
                    })
                    ->badge()
                    ->color(function (string $state): string {
                        return 'success';
                    }),

                // TextColumn::make('teams')
                //     ->label('Teams')
                //     ->formatStateUsing(function ($state, User $user) {
                //         $teams = $user->teams->unique('id')->pluck('name');
                //         return $teams->implode(" ");
                //     })
                //     ->badge()
                //     // ->separator(", ")
                //     // ->listWithLineBreaks()
                //     ->limit(50),

                TextColumn::make('teams')
                    ->label('Teams')
                    ->formatStateUsing(function ($state, User $user) {
                        $teams = $user->teams->unique('id')->pluck('name');
                        return $teams->join(', ');
                    })
                    // ->badge()
                    // ->separator('|')
                    ->limit(40)
                    ->wrap(),

                SpatieMediaLibraryImageColumn::make('avatar')->circular(),

                TextColumn::make('created_at')
                    ->label('Register at')
                    ->date()
                    ->sortable()->searchable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
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
            //
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
