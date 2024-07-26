<?php

namespace App\Filament\Resources\MaintenanceResource\Pages;

use Filament\Actions;
use Filament\Resources\Components\Tab;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\MaintenanceResource;

class ListMaintenances extends ListRecords
{
    protected static string $resource = MaintenanceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return [
           MaintenanceResource\Widgets\MaintenanceGeneralOverview::class,
        ];
    }

    public function getTabs(): array
    {
        return [
            'all' => Tab::make('Todas'),
            'Em andamento' => Tab::make('Em andamento')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', 'Aberta', true)),
            'Encerrada' => Tab::make('Encerradas')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', 'Fechada', false)),
        ];
    }
}
