<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;
use App\Models\Material;
use Carbon\Carbon;

class MaterialAvailability extends ChartWidget
{
    protected static ?string $heading = 'Materiais';

    protected static ?string $pollingInterval = '10s';

    protected function getData(): array
    {
        $activeFilter = $this->filter;
        $dateFilter = null;

        switch ($activeFilter) {
            case 'today':
                $dateFilter = Carbon::today();
                break;

            case 'week':
                $dateFilter = [
                    Carbon::now()->startOfWeek(),
                    Carbon::now()->endOfWeek()
                ];
                break;

            case 'month':
                $dateFilter = [
                    Carbon::now()->startOfMonth(),
                    Carbon::now()->endOfMonth()
                ];
                break;

            case 'year':
                $dateFilter = [
                    Carbon::now()->startOfYear(),
                    Carbon::now()->endOfYear()
                    ];
                break;

            default:
                $dateFilter = null;
        }

        // Filtros para materiais
        $allQuery = Material::query();
        $availableQuery = Material::query();
        $unavailableQuery = Material::query();
        $borrowedQuery = Material::query();
        $maintenanceQuery = Material::query();

        if ($activeFilter && $dateFilter) {
            if ($activeFilter == 'today') {
                $allQuery->whereDate('updated_at', $dateFilter);
                $availableQuery->whereDate('updated_at', $dateFilter);
                $unavailableQuery->whereDate('updated_at', $dateFilter);
                $borrowedQuery->whereDate('updated_at', $dateFilter);
                $maintenanceQuery->whereDate('updated_at', $dateFilter);
            } else {
                $allQuery->whereBetween('updated_at', $dateFilter);
                $availableQuery->whereBetween('updated_at', $dateFilter);
                $unavailableQuery->whereBetween('updated_at', $dateFilter);
                $borrowedQuery->whereBetween('updated_at', $dateFilter);
                $maintenanceQuery->whereDate('updated_at', $dateFilter);
            }
        }

        $all = $allQuery->count();
        $available = $availableQuery->where('status', 'Disponível')->count();
        $unavailable = $unavailableQuery->where('status', 'Indisponível')->count();
        $borrowed = $borrowedQuery->where('status', 'Cautelado')->count();
        $maintenance = $maintenanceQuery->where('status', 'Manutenção')->count();


        return [
            'labels' => [
                'Todos',
                'Disponível',
                'Indisponível',
                'Cautelado',
                'Manutenção',
            ],

            'datasets' => [
                [
                    'label' => 'Disponibilidade de Material',
                    'data' => [$all, $available, $unavailable, $borrowed, $maintenance],
                    'backgroundColor' => 'rgb(56, 189, 248)',
                    'borderColor' => '#9BD0F5',
                    'backgroundColor' => [
                        '#081c15',
                        '#1b4332',
                        '#2d6a4f',
                        '#40916c',
                        '#52b788',
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
