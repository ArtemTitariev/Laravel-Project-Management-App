<?php

namespace App\Filament\Employee\Resources;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use App\Filament\Employee\Resources\TeamResource\Pages;
use App\Filament\Employee\Resources\TeamResource\RelationManagers\MembersRelationManager;

class TeamResource extends Resource
{
    protected static ?string $model = \App\Models\Team::class;

    protected static ?string $navigationGroup = 'Teams';
    protected static ?string $navigationIcon = 'heroicon-o-user-group';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Grid::make([
                    'default' => 1,
                ])->schema([
                    Forms\Components\TextInput::make('name')
                        ->string()
                        ->maxLength(255)
                        ->required(),

                    Forms\Components\Select::make('members')
                        ->label('Members')
                        ->multiple()
                        ->relationship('members', 'full_name')
                        ->preload(),
                ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->query(
                \App\Models\Team::whereHas('members', function ($query) {
                    $query->where('user_id', auth()->user()->id);
                })
            )
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->sortable()->searchable()
                    ->wrap(),

                Tables\Columns\TextColumn::make('members.full_name')
                    ->label('All Members')
                    ->listWithLineBreaks()
                    ->bulleted()
                    ->limitList(3)
                    ->expandableLimitedList()
                    ->searchable(),

                Tables\Columns\TextColumn::make('members_count')
                    ->label('Number of Members')
                    ->counts('members', 'id')
                    ->badge()
                    ->color('info'),

                Tables\Columns\TextColumn::make('created_at')
                    ->date()
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
            ])
            ->bulkActions([
                //
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
            'view' => Pages\ViewTeam::route('/{record}'),
        ];
    }
}
