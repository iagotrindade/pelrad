<?php

namespace App\Filament\Resources\LoanResource\Pages;

use Carbon\Carbon;
use App\Models\User;
use Filament\Actions;
use App\Models\Material;
use App\Models\Component;
use App\Models\Configuration;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use App\Filament\Resources\LoanResource;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;
use Filament\Notifications\Actions\Action;
use Illuminate\Database\Eloquent\Collection;

class EditLoan extends EditRecord
{
    protected static string $resource = LoanResource::class;

    public $authUser;
    public $recipients;

    public function __construct()
    {
        $this->authUser = auth()->user();
        $this->recipients = User::all();
    }

    protected function handleRecordUpdate(Model $record, array $data): Model
    {
        // Obter os dados antigos do militar
        $oldData = [
            'to' => $record->to,
            'graduation' => $record->graduation,
            'name' => $record->name,
            'idt' => $record->idt,
            'contact' => $record->contact,
            'return_date' => $record->return_date,
        ];

        // Atualizar o status de cada material
        $materialsInfo = json_decode($record['materials_info'], true);
        if (is_array($materialsInfo)) {
            $newStatus = ($data['status'] == 'Fechada') ? 'Disponível' : 'Cautelado';
            foreach ($materialsInfo as $material) {
                if (isset($material['id'])) {
                    Material::where('id', $material['id'])->update(['status' => $newStatus]);
                }
            }
        }

        // Verificar se os dados do militar mudaram
        $militaryDataChanged = array_diff_assoc($oldData, [
            'to' => $data['to'] ?? null,
            'graduation' => $data['graduation'] ?? null,
            'name' => $data['name'] ?? null,
            'idt' => $data['idt'] ?? null,
            'contact' => $data['contact'] ?? null,
            'return_date' => $data['return_date'] ?? null,
        ]);

        // Gerar um novo PDF apenas se os dados do militar mudaram
        if (!empty($militaryDataChanged)) {
            $data = $this->generatePDF($data, $record);
        }

        

        // Atualizar o registro no banco
        $record->update($data);

        // Enviar notificação
        Notification::make()
            ->title('Cautela modificada')
            ->icon('heroicon-o-rectangle-stack')
            ->body($this->authUser->name . ' alterou o status da cautela do ' . $record->to . ' para ' . $data['status'] . '')
            ->actions([
                Action::make('Visualizar')
                    ->link()
                    ->url(LoanResource::getUrl('edit', ['record' => $record]))
            ])
            ->sendToDatabase($this->recipients);

        return $record;
    }

    /**
     * Função para gerar o PDF atualizado
     */
    protected function generatePDF($data, $record)
    {
        $data['material_group'] = json_decode($record['loan_material_base_data'], true);
        $data['personalized_material_group'] = json_decode($record['loan_personalized_material_data'], true);

        $groupComponents = collect($data['material_group'][0]['groupComponents'])->map(function ($componentData) {
            return (new Component())->forceFill($componentData)->setRawAttributes($componentData, true);
        });
        $groupComponents = new Collection($groupComponents);
        $data['material_group'][0]['groupComponents'] = $groupComponents;

        $groupMaterials = collect($data['material_group'][0]['materials'])->map(function ($materialData) {
            return (new Material())->forceFill($materialData)->setRawAttributes($materialData, true);
        });
        $data['material_group'][0]['materials'] = $groupMaterials;
        $groupMaterials = new Collection($groupMaterials);

        $configuration = Configuration::find(1);
        $data['from'] = $configuration->organization;
        $data['file'] = '/storage/loans/Cautela ' . $data['graduation'] . ' - ' . $data['name'] . ' ' . $data['to'] . ' ' . Carbon::now()->format('d.m.Y H\hi') . '.pdf';
        $data['return_file'] = '/storage/loans/Descautela ' . $data['graduation'] . ' - ' . $data['name'] . ' ' . $data['to'] . ' ' . Carbon::now()->format('d.m.Y H\hi') . '.pdf';

        // Deletar o PDF antigo
        $loanPath = str_replace('storage/', '', $record->file);
        $returnPath = str_replace('storage/', '', $record->return_file);

        if (Storage::disk('public')->exists($loanPath)) {
            Storage::disk('public')->delete($loanPath);
            Storage::disk('public')->delete($returnPath);
        }

        Pdf::loadView('reports.generate-loan-pdf', ['data' => $data, 'config' => $configuration])->save(public_path() . '' . $data['file'] . '')->stream('download.pdf');

        $data['loan_type'] = 'descautela';
        
        Pdf::loadView('reports.generate-loan-pdf', ['data' => $data, 'config' => $configuration])->save(public_path() . '' . $data['return_file'] . '')->stream('descautela.pdf');

        $data['file'] = 'loans/Cautela ' . $data['graduation'] . ' - ' . $data['name'] . ' ' . $data['to'] . ' ' . Carbon::now()->format('d.m.Y H\hi') . '.pdf';
        $data['return_file'] = 'loans/Descautela ' . $data['graduation'] . ' - ' . $data['name'] . ' ' . $data['to'] . ' ' . Carbon::now()->format('d.m.Y H\hi') . '.pdf';

        return $data;
    }

    
    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make()->before(function ($record) {
                $relativePath = str_replace('/storage', 'public', $record->file);

                if (Storage::exists($relativePath)) {
                    Storage::delete($relativePath);
                }

                Notification::make()
                    ->title('Cautela deletada')
                    ->icon('heroicon-o-rectangle-stack')
                    ->body($this->authUser->name . ' deletou a cautela ' . $record->name . '.')
                    ->sendToDatabase($this->recipients);
            }),
        ];
    }
}
