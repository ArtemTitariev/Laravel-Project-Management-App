<?php

namespace App\Filament\Admin\Resources;

use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use App\Filament\Admin\Resources\TeamResource\Pages;
use App\Filament\Admin\Resources\TeamResource\RelationManagers\MembersRelationManager;

class TeamResource extends Resource
{

    protected static ?string $model = \App\Models\Team::class;

    protected static ?string $navigationGroup = 'Team Management';
    protected static ?string $navigationIcon = 'heroicon-o-user-group';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                \Filament\Forms\Components\Grid::make([
                    'default' => 1,
                ])->schema([
                    \Filament\Forms\Components\TextInput::make('name')
                        ->string()
                        ->maxLength(255)
                        ->required(),

                    \Filament\Forms\Components\Select::make('members')
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
            ->columns([
                \Filament\Tables\Columns\TextColumn::make('name')
                    ->sortable()->searchable()
                    ->wrap(),

                \Filament\Tables\Columns\TextColumn::make('members.full_name')
                    ->label('All Members')
                    ->listWithLineBreaks()
                    ->bulleted()
                    ->limitList(3)
                    ->expandableLimitedList()
                    ->searchable(),

                \Filament\Tables\Columns\TextColumn::make('members_count')
                    ->label('Number of Members')
                    ->counts('members', 'id')
                    ->badge()
                    ->color('info'),

                \Filament\Tables\Columns\TextColumn::make('created_at')
                    ->date()
                    ->sortable(),


            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make()
                    ->action(function ($data, $record) {
                        if ($record->projects->isNotEmpty()) {
                            sendErrorNotification('Team', 'Projects', $record->name);
                        }
                    }),
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
            'view' => Pages\ViewTeam::route('/{record}'),
            'edit' => Pages\EditTeam::route('/{record}/edit'),
        ];
    }
}
