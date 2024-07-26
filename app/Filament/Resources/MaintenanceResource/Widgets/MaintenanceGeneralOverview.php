<?php

namespace App\Filament\Resources\MaintenanceResource\Widgets;

use App\Models\Maintenance;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class MaintenanceGeneralOverview extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Total', Maintenance::all()->count()),

            Stat::make('Em andamento', Maintenance::where('status', 'Em andamento')->count()),
                    
            Stat::make('Encerrada', Maintenance::where('status', 'Encerrada')->count()),
        ];
    }
}
