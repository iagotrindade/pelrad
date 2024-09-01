<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Network;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Repeater;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\NetworkResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\NetworkResource\RelationManagers;

class NetworkResource extends Resource
{
    protected static ?string $model = Network::class;

    protected static ?string $navigationLabel = 'Redes Rádio';

    protected static ?string $modelLabel = 'Redes';

    protected static ?string $recordTitleAttribute = 'name';

    protected static ?int $navigationSort = 4;

    protected static ?string $navigationIcon = 'heroicon-o-signal';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make()
                    ->schema([
                        TextInput::make('name')
                        ->label('Nome da Rede')
                        ->required(),
                    TextInput::make('frequency')
                        ->label('Frequência')
                        ->required(),
                    TextInput::make('alternative_frequency')
                        ->label('Frequência Alternativa')
                        ->required(),
                    ]),

                Section::make()
                    ->schema([
                        Repeater::make('stations_data')
                            ->schema([
                            TextInput::make('station_name')
                                ->required()
                                ->label('Nome do Posto'),

                            TextInput::make('station_slug')
                                ->label('Indicativo do Posto')
                                ->required(),

                            Toggle::make('is_pdr')
                                ->required()
                                ->label('Posto Diretor'),
                        ])
                        ->columns(4)
                        ->label('Postos')
                        ->collapsible()
                        ->defaultItems(3)
                        ->columns(2)
                ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
        ->columns([
            TextColumn::make('name')
                ->searchable()
                ->label('Nome'),
            TextColumn::make('frequency')
                ->searchable()
                ->label('Frequência'),
            TextColumn::make('alternative_frequency')
                ->label('Frequência Alternativa')
                ->searchable(),
            TextColumn::make('created_at')
                ->dateTime()
                ->label('Criado em')
                ->formatStateUsing(function ($state) {
                    return \Carbon\Carbon::parse($state)->translatedFormat('d M Y \à\s H:i');
                })
                ->searchable()
                ->sortable(),
            TextColumn::make('download')
                ->label('PDF')
                ->url(fn (Network $record): string => 'http://filament-app.test/'.$record->file.'')
                ->default('Download')
                ->icon('heroicon-m-arrow-down-tray')
                ->openUrlInNewTab()
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
            'index' => Pages\ListNetworks::route('/'),
            'create' => Pages\CreateNetwork::route('/create'),
            'edit' => Pages\EditNetwork::route('/{record}/edit'),
        ];
    }
}
