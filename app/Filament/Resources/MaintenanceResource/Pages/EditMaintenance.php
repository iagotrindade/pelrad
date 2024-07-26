<?php

namespace App\Filament\Resources\MaintenanceResource\Pages;

use App\Models\User;
use Filament\Actions;
use App\Models\Material;
use App\Models\Maintenance;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;
use Filament\Notifications\Actions\Action;
use App\Filament\Resources\MaintenanceResource;

class EditMaintenance extends EditRecord
{
    protected static string $resource = MaintenanceResource::class;

    public $authUser;
    public $recipients;

    public function __construct()
    {
        $this->authUser = auth()->user();
        $this->recipients = User::all();
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make()->before(function ($record) {
                $authUser = auth()->user();
                $recipients = User::all();

                // Atualizar o status de cada material para 'Disponível'
                $oldInfo = json_decode($record['old_info'], true);
                // Verificar se a decodificação foi bem-sucedida e se materialsInfo é um array
                if (is_array($record['materials'])) {
                    // Atualizar o status de cada material para 'Cautelado'
                    foreach ($record['materials'] as $key => $id) {
                        Material::where('id', $id)->update(['status' => $oldInfo[$key]['old_status']]); 
                    }
                }

                if (Storage::exists('public/'.$record->file)) {
                    Storage::delete('public/'.$record->file);
                } 

                Notification::make()
                    ->title('Manutenção deletada')
                    ->icon('heroicon-o-wrench-screwdriver') 
                    ->body($authUser->name . ' deletou a manutenção ' . $record->name . '.')
                ->sendToDatabase($recipients);
            }),
        ];
    }

    protected function handleRecordUpdate(Model $record, array $data): Model
    {
        // Decodifica as informações antigas do material
        $oldInfo = json_decode($record->old_info, true);
        $newInfo = $data['materials'];

        if ($data['status'] == 'Encerrada') {
            // Verifica se a decodificação foi bem-sucedida e se oldInfo é um array
            if (is_array($oldInfo)) {
                // Atualiza o status de cada material para o status antigo
                foreach ($oldInfo as $info) {
                    if (isset($info['material_id'])) {
                        Material::where('id', $info['material_id'])->update(['status' => $info['old_status']]);
                    }
                }
            }
        } else {
            // Verifica se newInfo é um array
            if (is_array($newInfo)) {
                // Cria um array com os IDs dos materiais novos
                $newMaterialIds = array_values($newInfo);
    
                // Atualiza o status dos materiais removidos
                foreach ($oldInfo as $info) {
                    if (isset($info['material_id'])) {
                        if (!in_array($info['material_id'], $newMaterialIds)) {
                            Material::where('id', $info['material_id'])->update(['status' => $info['old_status']]);
                        } else {
                            Material::where('id', $info['material_id'])->update(['status' => 'Manutenção']);
                        }
                    }
                }
    
                // Atualiza o status dos novos materiais para 'Manutenção'
                foreach ($newMaterialIds as $materialId) {
                    if (!in_array($materialId, array_column($oldInfo, 'material_id'))) {
                        Material::where('id', $materialId)->update(['status' => 'Manutenção']);
                    }
                }
            }
        }
        

        $record->update($data);

        Notification::make()
            ->title('Manutenção modificada')
            ->icon('heroicon-o-wrench-screwdriver') 
            ->body(''.$this->authUser->name.' alterou a manutenão '.$record->description.'.')
            ->actions([
                Action::make('Visualizar')
                    ->link()
                    ->url(MaintenanceResource::getUrl('edit', ['record' => $record]))
            ])
            ->sendToDatabase($this->recipients);

        return $record;
    }
}
