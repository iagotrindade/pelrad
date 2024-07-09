<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use App\Models\User;
use App\Models\Material;
use App\Models\Loan;

class GeneralOverview extends BaseWidget
{
    protected int | string | array $columnSpan = 'full';
    protected static ?string $pollingInterval = '10s';

    protected function getStats(): array
    {
        return [
            Stat::make('Usuários', User::all()->count())
                ->descriptionIcon('heroicon-m-user-group')
                ->description('Usuários cadastrados'),

            Stat::make('Materiais', Material::all()->count())
                ->descriptionIcon('heroicon-m-cube')
                ->description('Materiais cadastrados'),

            Stat::make('Cautelas', Loan::all()->count())
                ->descriptionIcon('heroicon-m-clipboard-document-list')
                ->description('Cautelas existentes'),
        ];
    }
}
