<?php

namespace App\Filament\Resources\ConfigurationResource\Pages;

use App\Filament\Resources\ConfigurationResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Filament\Forms\Components\Wizard\Step;
use Filament\Forms\Components\TextInput;

class CreateConfiguration extends CreateRecord
{
    protected static string $resource = ConfigurationResource::class;

    use CreateRecord\Concerns\HasWizard;

    protected function getSteps(): array
    {

        return [
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
        ];
    }
}
