<?php

namespace App\Filament\Exports;

use Carbon\Carbon;
use App\Models\Material;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Models\Export;

class MaterialExporter extends Exporter
{
    protected static ?string $model = Material::class;

    public static function getColumns(): array
    {
        return [
            ExportColumn::make('name')
                ->label('Nome'),
            ExportColumn::make('description')
                ->label('Descrição'),
            ExportColumn::make('status')
                ->label('Status'),
            ExportColumn::make('serial_number')
                ->label('Serial number'),
            ExportColumn::make('record_number')
                ->label('Número da ficha'),
            ExportColumn::make('patrimony_number')
                ->label('Número de patrimônio'),
            ExportColumn::make('patrimony_value')
                ->label('Valor do patrimônio'),
            ExportColumn::make('inclusion_document')
                ->label('Documento de inclusão'),
            ExportColumn::make('inclusion_date')
                ->label('Data de inclusão')
                ->formatStateUsing(function ($state) {
                    return Carbon::parse($state)->format('d-m-Y \à\s H:i');
                }),
        ];
    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        $body = 'Sua exportação dos materiais está completa e ' . number_format($export->successful_rows) . ' ' . str('linha')->plural($export->successful_rows) . ' foi exportada.';

        if ($failedRowsCount = $export->getFailedRowsCount()) {
            $body .= ' ' . number_format($failedRowsCount) . ' ' . str('linha')->plural($failedRowsCount) . ' falhou ao ser importada.';
        }

        return $body;
    }
}
