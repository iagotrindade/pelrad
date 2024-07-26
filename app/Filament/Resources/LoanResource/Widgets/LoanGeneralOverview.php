<?php

namespace App\Filament\Resources\LoanResource\Widgets;

use App\Models\Loan;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class LoanGeneralOverview extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Total', Loan::all()->count()),

            Stat::make('Abertas', Loan::where('status', 'Aberta')->count()),
                    
            Stat::make('Fechadas', Loan::where('status', 'Fechada')->count()),
        ];
    }
}
