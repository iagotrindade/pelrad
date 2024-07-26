<?php

namespace App\Filament\Resources\MaintenanceResource\Pages;

use Filament\Actions;
use App\Models\Material;
use Filament\Resources\Pages\CreateRecord;
use App\Filament\Resources\MaintenanceResource;

class CreateMaintenance extends CreateRecord
{
    protected static string $resource = MaintenanceResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {    
        if (isset($data['materials']) && is_array($data['materials'])) {
            // Coleta os materiais de uma só vez para reduzir consultas ao banco
            $materials = Material::whereIn('id', $data['materials'])->get();
    
            foreach ($materials as $key => $material) {
                $data['old_info'][$key] = [
                    'material_id' => $material->id,
                    'old_status' => $material->status,
                ];
                $material->update(['status' => 'Manutenção']);
            }
            
            $data['old_info'] = json_encode($data['old_info']);
        }
        return $data;
    }
}
