<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;
use App\Models\Material;
use Carbon\Carbon;

class MaterialAvailability extends ChartWidget
{
    protected static ?string $heading = 'Materiais';
    protected static ?string $maxHeight = '400px';

    protected static ?string $pollingInterval = '10s';

    protected function getData(): array
    {
        // Definir o filtro de data com base no filtro ativo
        $dateFilter = match ($this->filter) {
            'today' => Carbon::today(),
            'week' => [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()],
            'month' => [Carbon::now()->startOfMonth(), Carbon::now()->endOfMonth()],
            'year' => [Carbon::now()->startOfYear(), Carbon::now()->endOfYear()],
            default => null,
        };

        // Query base para todos os materiais
        $materialQuery = Material::query();

        // Aplicar filtros de data, se houver
        if ($dateFilter) {
            if (is_array($dateFilter)) {
                $materialQuery->whereBetween('updated_at', $dateFilter);
            } else {
                $materialQuery->whereDate('updated_at', $dateFilter);
            }
        }

        // Clonar as queries antes de aplicar filtros de status
        $allQuery = clone $materialQuery;
        $availableQuery = clone $materialQuery;
        $unavailableQuery = clone $materialQuery;
        $borrowedQuery = clone $materialQuery;
        $maintenanceQuery = clone $materialQuery;

        // Contagens com base no status
        $statusCounts = [
            'Todos' => $allQuery->count(),
            'Disponível' => $availableQuery->where('status', 'Disponível')->count(),
            'Indisponível' => $unavailableQuery->where('status', 'Indisponível')->count(),
            'Cautelado' => $borrowedQuery->where('status', 'Cautelado')->count(),
            'Manutenção' => $maintenanceQuery->where('status', 'Manutenção')->count(),
        ];

        return [
            'labels' => array_keys($statusCounts),
            'datasets' => [
                [
                    'label' => 'Disponibilidade de Material',
                    'data' => array_values($statusCounts),
                    'backgroundColor' => 'rgb(56, 189, 248)',
                    'borderColor' => '#9BD0F5',
                    'backgroundColor' => [
                        '#179bef',
                        '#3bb4f3',
                        '#5ecdf7',
                        '#82e5fb',
                        '#a5feff',
                    ],
                ],
            ],
        ];
    }


    protected function getType(): string
    {
        return 'doughnut';
    }

    public function getDescription(): ?string
    {
        return 'Visão geral do Material';
    }

    protected function getFilters(): ?array
    {
        return [
            'today' => 'Hoje',
            'week' => 'Última semana',
            'month' => 'Último mês',
            'year' => 'Este ano',
        ];
    }
}
