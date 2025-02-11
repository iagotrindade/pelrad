<?php

namespace App\Filament\Resources\MaterialResource\Pages;

use App\Models\User;
use Filament\Actions;
use Illuminate\Database\Eloquent\Model;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;
use Filament\Notifications\Actions\Action;
use App\Filament\Resources\MaterialResource;
use Illuminate\Validation\ValidationException;

class EditMaterial extends EditRecord
{
    protected static string $resource = MaterialResource::class;

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
                Notification::make()
                    ->title('Material deletado')
                    ->icon('heroicon-o-cube') 
                    ->body($this->authUser->name . ' deletou o material ' . $record->name . '.')
                ->sendToDatabase($this->recipients);
            }),
        ];
    }
    
    protected function handleRecordUpdate(Model $record, array $data): Model
    {   
        if (
            ($record->status == 'Cautelado' && $data['status'] != 'Cautelado') ||
            ($record->status == 'Manutenção' && $data['status'] != 'Manutenção')
        ) {
            throw ValidationException::withMessages([
                'status' => 'Não é possível atualizar o material com status "Cautelado" ou "Manutenção".',
            ]);
        }
        
        $record->update($data);

        Notification::make()
            ->title('Material modificado')
            ->icon('heroicon-o-cube') 
            ->body(''.$this->authUser->name.' alterou os dados do material '.$data['name'].'.')
            ->actions([
                Action::make('Visualizar')
                    ->link()
                    ->url(MaterialResource::getUrl('edit', ['record' => $record]))
            ])
            ->sendToDatabase($this->recipients);

        return $record;
    }
}
