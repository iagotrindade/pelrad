<?php

namespace App\Filament\Resources\ReportResource\Widgets;

use App\Models\Category;
use App\Models\Configuration;
use App\Models\Loan;
use App\Models\User;
use App\Models\Material;
use App\Models\Activity;
use Filament\Infolists\Components\Section;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Spatie\Activitylog\Models\Activity as ModelsActivity;

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
                    ->url(route('report.users'), shouldOpenInNewTab: true),

                Stat::make('Relatório de Categorias', Category::all()->count())
                    ->descriptionIcon('heroicon-o-tag')
                    ->description('Dados das categorias')
                    ->color('success')
                    ->extraAttributes([
                        'class' => 'cursor-pointer',
                    ])
                    ->url(route('report.categories'), shouldOpenInNewTab: true),
                    

                Stat::make('Relatório de Materiais', Material::all()->count())
                    ->descriptionIcon('heroicon-m-cube')
                    ->description('Dados dos materiais')
                    ->color('success')
                    ->extraAttributes([
                        'class' => 'cursor-pointer',
                    ])
                    ->url(route('report.material'), shouldOpenInNewTab: true),

                Stat::make('Relatório de Cautelas', Loan::all()->count())
                    ->descriptionIcon('heroicon-m-clipboard-document-list')
                    ->description('Dados das cautelas')
                    ->color('success')
                    ->extraAttributes([
                        'class' => 'cursor-pointer',
                    ])
                    ->url(route('report.loans'), shouldOpenInNewTab: true),

                Stat::make('Relatório de Configurações', Configuration::all()->count())
                    ->descriptionIcon('heroicon-o-cog-8-tooth')
                    ->description('Dado da configuração de cautelas')
                    ->color('success')
                    ->extraAttributes([
                        'class' => 'cursor-pointer',
                    ])
                    ->url(route('report.configuration'), shouldOpenInNewTab: true),

                Stat::make('Relatório de Auditoria', ModelsActivity::all()->count())
                    ->descriptionIcon('heroicon-m-eye')
                    ->description('Dados de auditoria')
                    ->color('success')
                    ->extraAttributes([
                        'class' => 'cursor-pointer',
                    ])
                    ->url(route('report.audit'), shouldOpenInNewTab: true),
        ];
    }
}
