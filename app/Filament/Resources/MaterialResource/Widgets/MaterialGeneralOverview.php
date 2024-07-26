<?php

namespace App\Filament\Resources\MaterialResource\Widgets;

use App\Models\Material;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class MaterialGeneralOverview extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Total', Material::all()->count()),

            Stat::make('Disponíveis', Material::where('status', 'Disponível')->count()),
                    
            Stat::make('Indisponíveis', Material::where('status', 'Indisponível')->orWhere('status', 'Cautelado')->orWhere('status', 'Manutenção')->orWhere('status', 'Descarregado')->count()),
        ];
    }
}
