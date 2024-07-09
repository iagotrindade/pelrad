<?php

namespace App\Filament\Resources\ReportResource\Widgets;

use App\Models\Configuration;
use App\Models\Loan;
use App\Models\Type;
use App\Models\User;
use App\Models\Material;
use Filament\Infolists\Components\Section;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;

class GeneralOverview extends BaseWidget
{
    protected int | string | array $columnSpan = 'full';
    protected static ?string $pollingInterval = '10s';

    
    protected function getStats(): array
    {
        return [
                Stat::make('Relatório de Usuários', User::all()->count())
                    ->descriptionIcon('heroicon-m-user-group')
                    ->description('Dados dos usuários')
                    ->color('success')
                    ->extraAttributes([
                        'class' => 'cursor-pointer',
                    ])
                    ->url('', shouldOpenInNewTab: true),

                Stat::make('Relatório de Categorias', Type::all()->count())
                    ->descriptionIcon('heroicon-o-tag')
                    ->description('Dados das categorias')
                    ->color('success')
                    ->extraAttributes([
                        'class' => 'cursor-pointer',
                    ])
                    ->url('google.com', shouldOpenInNewTab: true),
                    

                Stat::make('Relatório de Materiais', Material::all()->count())
                    ->descriptionIcon('heroicon-m-cube')
                    ->description('Dados dos materiais')
                    ->color('success')
                    ->extraAttributes([
                        'class' => 'cursor-pointer',
                    ])
                    ->url('', shouldOpenInNewTab: true),

                Stat::make('Relatório de Cautelas', Loan::all()->count())
                    ->descriptionIcon('heroicon-m-clipboard-document-list')
                    ->description('Dados das cautelas')
                    ->color('success')
                    ->extraAttributes([
                        'class' => 'cursor-pointer',
                    ])
                    ->url('google.com', shouldOpenInNewTab: true),

                Stat::make('Relatório de Configurações', Configuration::all()->count())
                    ->descriptionIcon('heroicon-o-cog-8-tooth')
                    ->description('Dados da configuração de cautelas')
                    ->color('success')
                    ->extraAttributes([
                        'class' => 'cursor-pointer',
                    ])
                    ->url('google.com', shouldOpenInNewTab: true),

                Stat::make('Relatório de Auditoria', '-')
                    ->descriptionIcon('heroicon-m-eye')
                    ->description('Dados de auditoria')
                    ->color('success')
                    ->extraAttributes([
                        'class' => 'cursor-pointer',
                    ])
                    ->url('google.com', shouldOpenInNewTab: true),
        ];
    }
}
