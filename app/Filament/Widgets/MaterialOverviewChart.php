<?php

namespace App\Filament\Widgets;

use App\Models\Category;
use App\Models\Material;
use Filament\Widgets\ChartWidget;

class MaterialOverviewChart extends ChartWidget
{
    protected static ?string $heading = 'Diponibilidade Material Rádio';
    protected static ?int $sort = 3;
    protected int | string | array $columnSpan = 'full';
    protected static ?string $maxHeight = '450px';

    protected function getData(): array
    {
        $categories = Category::whereIn('name', [
            'ESTAÇÃO REPETIDORA GTR 8000',
            'RÁDIO APX 2000',
            'RÁDIO XTS 1500',
            'RÁDIO XTS 2500',
            'RÁDIO XTL 1500',
            'RÁDIO XTL 2200',
            'REPETIDORA DVR',
            'RF 7800V HH 001 (FALCON III)',
            'RF 7850M HH 001 MB (FALCON III MULTI-BANDA)',
            'MPR 9600 MP (FALCON II)',
            'BASE VEICULAR FALCON III',
            'NOTEBOOK ROBUSTECIDO'
        ])->get();

        $availability = [];
        $materialNames = [];

        foreach ($categories as $category) {
            // Conta os materiais disponíveis para cada categoria
            $materialCount = Material::where('categories_id', $category->id)
                ->where('status', 'Disponível')
                ->count();

            // Adiciona o nome da categoria e a contagem ao array
            $materialNames[] = $category->name;
            $availability[] = $materialCount;
        }

        return [
            'datasets' => [
                [
                    'label' => 'Materiais Disponíveis',
                    'data' => $availability, // Dados populados dinamicamente

                ],
            ],
            'labels' => $materialNames, // Nomes das categorias  
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }  
}
