<?php

namespace App\Filament\Resources\UpdateOracleResource\Pages;

use App\Filament\Resources\UpdateOracleResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListUpdateOracles extends ListRecords
{
    protected static string $resource = UpdateOracleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
