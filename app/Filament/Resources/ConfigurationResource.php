<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ConfigurationResource\Pages;
use App\Filament\Resources\ConfigurationResource\RelationManagers;
use App\Models\Configuration;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\Wizard;
use Filament\Forms\Components\Wizard\Step;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ConfigurationResource extends Resource
{
    protected static ?string $model = Configuration::class;

    protected static ?string $navigationIcon = 'heroicon-o-cog-8-tooth';

    protected static ?string $navigationLabel = 'Configurações';

    protected static ?string $modelLabel = 'Configurações';

    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Wizard::make([
                    Step::make('Organização Militar')
                        ->schema([
                            TextInput::make('organization')
                            ->label('Organização Militar')
                            ->required(),

                            TextInput::make('organization_slug')
                            ->label('Abreviação da OM')
                            ->required(),
                        ]),
                    Step::make('Companhia')
                        ->schema([
                            TextInput::make('company')
                            ->label('Companhia')
                            ->required(),

                            TextInput::make('squad')
                            ->label('Pelotão')
                            ->required(),

                            TextInput::make('squad_leader')
                            ->label('Comandante de Pelotão')
                            ->required(),

                            TextInput::make('company_leader')
                            ->label('Comandante de Companhia')
                            ->required(),
                        ])->columns(2),
                    Step::make('4ª Sessão')
                        ->schema([
                            TextInput::make('organization_s4')
                        ->label('Chefe do S4')
                        ->required(),
                    ]),
                ])->columnSpanFull()
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('organization_slug')
                    ->label('OM'),

                TextColumn::make('company')
                    ->searchable()
                    ->label('Companhia'),

                TextColumn::make('squad')
                    ->label('Pelotão'),

                TextColumn::make('squad_leader')
                    ->label('Comandante de Pelotão'),

                TextColumn::make('company_leader')
                    ->label('Comandante de Companhia'),

                TextColumn::make('organization_s4')
                    ->label('Chefe do S4'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([

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
            'index' => Pages\ListConfigurations::route('/'),
            'create' => Pages\CreateConfiguration::route('/create'),
            'edit' => Pages\EditConfiguration::route('/{record}/edit'),
        ];
    }
}
