<?php

namespace App\Filament\Resources\LoanResource\Pages;

use Filament\Actions;
use Illuminate\Database\Eloquent\Builder;
use Filament\Resources\Components\Tab;
use App\Filament\Resources\LoanResource;
use Filament\Resources\Pages\ListRecords;

class ListLoans extends ListRecords
{
    protected static string $resource = LoanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return [
            LoanResource\Widgets\LoanGeneralOverview::class,
        ];
    }

    public function getTabs(): array
    {
        return [
            'all' => Tab::make('Todas'),
            'Aberta' => Tab::make('Abertas')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', 'Aberta', true)),
            'Fechada' => Tab::make('Fechadas')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', 'Fechada', false)),
        ];
    }
}
