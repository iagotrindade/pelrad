<?php

namespace App\Filament\Imports;

use App\Models\Material;
use Filament\Actions\Imports\ImportColumn;
use Filament\Actions\Imports\Importer;
use Filament\Actions\Imports\Models\Import;

class MaterialImporter extends Importer
{
    protected static ?string $model = Material::class;

    public static function getColumns(): array
    {
        return [
            ImportColumn::make('images')
                ->requiredMapping()
                ->rules(['required', 'max:255']),
            ImportColumn::make('record_number')
                ->rules(['max:255']),
            ImportColumn::make('patrimony_number')
                ->rules(['max:255']),
            ImportColumn::make('patrimony_value')
                ->rules(['max:255']),
            ImportColumn::make('inclusion_document')
                ->rules(['max:255']),
            ImportColumn::make('inclusion_date')
                ->rules(['datetime']),
            ImportColumn::make('name')
                ->requiredMapping()
                ->rules(['required', 'max:255']),
            ImportColumn::make('serial_number')
                ->requiredMapping()
                ->rules(['required', 'max:255']),
            ImportColumn::make('description'),
            ImportColumn::make('status')
                ->requiredMapping()
                ->rules(['required', 'max:255']),
            ImportColumn::make('categories_id')
                ->numeric()
                ->rules(['integer']),
        ];
    }

    public function resolveRecord(): ?Material
    {
        // return Material::firstOrNew([
        //     // Update existing records, matching them by `$this->data['column_name']`
        //     'email' => $this->data['email'],
        // ]);

        return new Material();
    }

    public static function getCompletedNotificationBody(Import $import): string
    {
        $body = 'Your material import has completed and ' . number_format($import->successful_rows) . ' ' . str('row')->plural($import->successful_rows) . ' imported.';

        if ($failedRowsCount = $import->getFailedRowsCount()) {
            $body .= ' ' . number_format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' failed to import.';
        }

        return $body;
    }
}
