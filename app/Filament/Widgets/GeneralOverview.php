<?php

namespace App\Filament\Widgets;

use App\Models\Compliance;
use Carbon\Carbon;
use App\Models\Loan;
use App\Models\Maintenance;
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
        // Consulta para obter todas as cautelas (loans), incluindo o status
        $loans = Loan::selectRaw('DATE(created_at) as date, COUNT(*) as count, return_date, status')
            ->groupByRaw('DATE(created_at), return_date, status') // Agrupar por data de criação, return_date e status
            ->orderBy('date', 'asc')
        ->get();

        // Filtra e pluck a contagem total das loans por data
        $loanCounts = $loans->pluck('count')->toArray();

        // Preencher com zeros para datas sem registros, se necessário
        $startDate = Carbon::now()->subDays(6); // Últimos 7 dias
        $dateCounts = collect($loanCounts)->pad($startDate->diffInDays(Carbon::now()) + 1, 0);

        // Contar as loans com status "Em aberto" diretamente da coleção
        $openLoansCount = $loans->where('status', 'Aberta')->sum('count');

        // Verificar se há loans com prazo de devolução vencido
        $loansDeadlineExpiring = false;
        $deadLines = 0;
        // Iterar sobre as "loans" abertas e verificar a data de retorno
        foreach ($loans as $loan) {
            // Certifique-se de que 'return_date' seja uma instância válida de data
            $returnDate = Carbon::parse($loan->return_date);

            // Verifica se a data de retorno é maior ou igual à data atual
            if ($loan->status == 'Aberta' && $returnDate->lessThanOrEqualTo(Carbon::now())) {
                $loansDeadlineExpiring = true;
                $deadLines++;
            }
        }

        // Verificar se o pronto do dia foi gerado

        $commonStats = [
            Stat::make('Usuários', User::all()->count())
                ->descriptionIcon('heroicon-m-user-group')
                ->description('Usuários cadastrados')
                ->url('/users')
                ->color('primary'),
            Stat::make('Manutenções', Maintenance::all()->count())
                ->descriptionIcon('heroicon-m-user-group')
                ->description('Manutenções em andamento '.Maintenance::where('status', 'Em andamento')->count().'')
                ->url('/maintenances')
                ->color('primary'), 
        ];

        $lastCompliance = Compliance::whereDate('created_at', Carbon::today())->exists();

        if($lastCompliance) {
            $commonStats[] = Stat::make('Materiais', Material::all()->count())
            ->descriptionIcon('heroicon-m-cube')
            ->description('Materiais cadastrados')
            ->url('/materials')
            ->color('primary');
        }

        else {
            $commonStats[] = Stat::make('ATENÇÃO!!!', '!')
            ->descriptionIcon('heroicon-m-cube')
            ->description('Pronto de materiais não gerado')
            ->url('/reports')
            ->color('danger');
        }
        
        // Estatística de cautelas
        if ($loansDeadlineExpiring) {
            $commonStats[] = Stat::make('ATENÇÃO!!!', $deadLines)
                ->descriptionIcon('heroicon-m-clipboard-document-list')
                ->description("".str('Prazo')->plural($deadLines)." de devolução ".str('vencido')->plural($deadLines)."!!!")
                ->chart($dateCounts->toArray())
                ->url('/loans')
                ->color('danger');
        } else {
            $commonStats[] = Stat::make('Cautelas', count($loans))
                ->descriptionIcon('heroicon-m-clipboard-document-list')
                ->description("Cautelas em aberto: {$openLoansCount}")
                ->url('/loans')
                ->chart($dateCounts->toArray())
                ->color('primary');
        }
        
        return $commonStats;
    }
}
