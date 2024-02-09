<?php

namespace App\Filament\Admin\Resources\TeamResource\RelationManagers;

use Filament\Forms;
use Filament\Tables;
use App\Models\Position;
use App\Models\UserRole;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Resources\RelationManagers\RelationManager;

class MembersRelationManager extends RelationManager
{
    protected static string $relationship = 'members';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Fieldset::make('Personal info')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->label('First name')
                            ->string()
                            ->maxLength(255)
                            ->required(),
                        Forms\Components\TextInput::make('second_name')
                            ->label('Second name')
                            ->string()
                            ->maxLength(255)
                            ->required(),
                        Forms\Components\TextInput::make('email')
                            ->email()
                            ->dehydrated(fn ($state) => filled($state))
                            ->required(fn (string $context): bool => $context === 'create')
                            ->unique(ignoreRecord: true),

                        myPasswordField(), // helper
                    ]),
                Forms\Components\Fieldset::make('Position and role')
                    ->schema([
                        Forms\Components\Select::make('position_id')
                            ->label('Position')
                            ->options(Position::all()->pluck('name', 'id'))
                            ->searchable()
                            ->required(),
                        Forms\Components\Select::make('user_role_id')
                            ->label('Role')
                            ->options(UserRole::all()->pluck('name', 'id'))
                            ->searchable()
                            ->required(),
                    ]),

                Forms\Components\SpatieMediaLibraryFileUpload::make('avatar'),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('full_name')
            ->columns([
                Tables\Columns\TextColumn::make('full_name')
                    ->sortable(),
                Tables\Columns\TextColumn::make('email')
                    ->sortable(),
                Tables\Columns\TextColumn::make('position.name')
                    ->label('Position')
                    ->badge()
                    ->sortable(),
                Tables\Columns\TextColumn::make('userRole.name')
                    ->label('Role')
                    ->badge()
                    ->color(function (string $state): string {
                        return 'success';
                    })
                    ->sortable(),

                Tables\Columns\SpatieMediaLibraryImageColumn::make('avatar')
                    ->defaultImageUrl('/storage/no-image-available.jpg')
                    ->circular(),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
                Tables\Actions\AttachAction::make()
                    ->preloadRecordSelect(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DetachAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DetachBulkAction::make(),
                ]),
            ]);
    }
}
