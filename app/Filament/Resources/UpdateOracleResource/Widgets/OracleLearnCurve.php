<?php

namespace App\Filament\Resources\UpdateOracleResource\Widgets;

use App\Models\Oracle;
use Filament\Widgets\ChartWidget;

use Illuminate\Support\Facades\DB;

class OracleLearnCurve extends ChartWidget
{
    protected static ?string $heading = 'Curva de Aprendizado do Antigão';

    protected int | string | array $columnSpan = 'full';

    protected static ?string $maxHeight = '300px';

    protected function getData(): array
    {
        // Agrupa os registros por mês e conta a quantidade de registros por mês
        $knowledgeByMonth = Oracle::select(DB::raw('MONTH(created_at) as month'), DB::raw('count(*) as total'))
            ->groupBy('month')
            ->orderBy('month')
            ->get()
            ->pluck('total', 'month')
            ->toArray();

        // Array com os nomes dos meses
        $months = ['Jan', 'Fev', 'Mar', 'Abr', 'Mai', 'Jun', 'Jul', 'Ago', 'Set', 'Out', 'Nov', 'Dez'];

        // Preenche os dados do gráfico com os valores correspondentes de registros por mês, ou zero se não houver registros
        $data = [];
        for ($i = 1; $i <= 12; $i++) {
            $data[] = $knowledgeByMonth[$i] ?? 0;
        }

        return [
            'labels' => $months,
            'datasets' => [
                [
                    'label' => 'Registros por Mês',
                    'data' => $data,
                    'backgroundColor' => 'rgb(56, 189, 248)',
                    'borderColor' => '#9BD0F5',
                    'backgroundColor' => [
                        '#179bef',
                        '#3bb4f3',
                        '#5ecdf7',
                    ],
                ],
            ],
        ];
    }

    public function getDescription(): ?string
    {
        return 'Este gráfico representa a quantidade de dados adicionados a base de conhecimento do Antigão por mês.';
    }

    protected function getType(): string
    {
        return 'line';
    }
}
