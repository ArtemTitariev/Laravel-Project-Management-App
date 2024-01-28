<?php

namespace App\Filament\Resources;

use Filament\Forms;
use App\Models\Team;
use App\Models\User;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Select;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use App\Filament\Resources\TeamResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\TeamResource\RelationManagers;
use App\Filament\Resources\TeamResource\RelationManagers\MembersRelationManager;

class TeamResource extends Resource
{

    protected static ?string $model = Team::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Grid::make([
                    'default' => 1,
                ])->schema([
                    TextInput::make('name')->required(),

                    Select::make('members')
                        ->label('Members')
                        ->multiple()
                        ->relationship('members', 'full_name')
                        ->preload()
                    //
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

                TextColumn::make('members.full_name')
                    ->label('All members')
                    ->listWithLineBreaks()
                    ->bulleted()
                    ->limitList(3)
                    ->expandableLimitedList()
                    ->searchable(),

                TextColumn::make('members_count')
                    ->label('Amount of members')
                    ->counts('members', 'id')
                    ->badge()
                    ->color('info'),

                TextColumn::make('created_at')
                    ->date()
                    ->sortable(),


            ])
            ->filters([
                // SelectFilter::make('members')
                //     ->label('Members')
                //     ->relationship('members', 'full_name')
                //     ->multiple(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
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
            MembersRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTeams::route('/'),
            'create' => Pages\CreateTeam::route('/create'),
            'edit' => Pages\EditTeam::route('/{record}/edit'),
        ];
    }
}
