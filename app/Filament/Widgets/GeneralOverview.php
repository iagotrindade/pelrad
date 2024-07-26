<?php

namespace App\Filament\Widgets;

use Carbon\Carbon;
use App\Models\Loan;
use App\Models\User;
use App\Models\Material;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;

class GeneralOverview extends BaseWidget
{
    protected int | string | array $columnSpan = 'full';
    protected static ?string $pollingInterval = '10s';

    protected function getStats(): array
    {
        $loans = Loan::selectRaw('DATE(created_at) as date, COUNT(*) as count')
            ->groupBy('date')
            ->orderBy('date', 'asc')
            ->get()
            ->pluck('count')
            ->toArray();

        // Preencher com zeros para datas sem registros, se necessário
        $startDate = Carbon::now()->subDays(6); // Últimos 7 dias
        $dateCounts = collect($loans)->pad($startDate->diffInDays(Carbon::now()) + 1, 0);

        return [
            Stat::make('Usuários', User::all()->count())
                ->descriptionIcon('heroicon-m-user-group')
                ->description('Usuários cadastrados'),

            Stat::make('Materiais', Material::all()->count())
                ->descriptionIcon('heroicon-m-cube')
                ->description('Materiais cadastrados'),

            Stat::make('Cautelas', count($loans))
                ->descriptionIcon('heroicon-m-clipboard-document-list')
                ->description('Cautelas existentes')
                ->chart($dateCounts->toArray()),
        ];
    }
}
