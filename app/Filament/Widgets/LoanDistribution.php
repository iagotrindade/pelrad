<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;
use Carbon\Carbon;
use App\Models\Loan;

class LoanDistribution extends ChartWidget
{
    protected static ?string $heading = 'Cautelas';

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
        $allQuery = Loan::query();
        $openQuery = Loan::query();
        $closedQuery = Loan::query();

        if ($activeFilter && $dateFilter) {
            if ($activeFilter == 'today') {
                $allQuery->whereDate('updated_at', $dateFilter);
                $openQuery->whereDate('updated_at', $dateFilter);
                $closedQuery->whereDate('updated_at', $dateFilter);
            } else {
                $allQuery->whereBetween('updated_at', $dateFilter);
                $openQuery->whereBetween('updated_at', $dateFilter);
                $closedQuery->whereBetween('updated_at', $dateFilter);
            }
        }

        $all = $allQuery->count();
        $open = $openQuery->where('status', 'Aberta')->count();
        $closed = $closedQuery->where('status', 'Fechada')->count();

        return [
            'labels' => [
                'Todas',
                'Abertas',
                'Fechadas',
            ],

            'datasets' => [
                [
                    'label' => 'Disponibilidade de Material',
                    'data' => [$all, $open, $closed],
                    'backgroundColor' => 'rgb(56, 189, 248)',
                    'borderColor' => '#9BD0F5',
                    'backgroundColor' => [
                        '#081c15',
                        '#1b4332',
                        '#2d6a4f',
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
        return 'Visão geral de Cautelas';
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
