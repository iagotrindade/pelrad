<?php

namespace App\Filament\Resources\MaterialResource\Pages;

use App\Filament\Resources\MaterialResource;
use Filament\Actions;
use Filament\Actions\Action;
use Filament\Resources\Pages\ListRecords;
use Filament\Resources\Components\Tab;
use Illuminate\Database\Eloquent\Builder;


class ListMaterials extends ListRecords
{
    protected static string $resource = MaterialResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
    protected function getHeaderWidgets(): array
    {
        return [
            MaterialResource\Widgets\MaterialGeneralOverview::class,
        ];
    }

    public function getTabs(): array
    {
        return [
            'all' => Tab::make('Todos'),
            'Disponível' => Tab::make('Disponível')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', 'Disponível', true)),
            'Indisponível' => Tab::make('Indisponível')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', 'Indisponível', false)),
            'Cautelado' => Tab::make('Cautelado')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', 'Cautelado', false)),
            'Manutenção' => Tab::make('Manutenção')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', 'Manutenção', false)),
            'Descarregado' => Tab::make('Descarregado')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', 'Descarregado', false)),
        ];
    }
}
