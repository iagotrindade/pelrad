<?php

namespace App\Filament\Resources\NetworkResource\Pages;

use App\Models\User;
use Filament\Actions;
use App\Models\Configuration;
use Illuminate\Support\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Database\Eloquent\Model;
use Filament\Notifications\Notification;
use Filament\Notifications\Actions\Action;
use Filament\Resources\Pages\EditRecord;
use App\Filament\Resources\NetworkResource;
use App\Models\Network;

class EditNetwork extends EditRecord
{
    public $authUser;
    public $recipients;
    public $configurations;

    protected static string $resource = NetworkResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make()->before(function ($record) {
                $this->authUser = auth()->user();
                $this->recipients = User::all();
        
                $networkFile = 'storage/'.$record->file.'';
        
                if($networkFile) {
                    // Verificar se o arquivo existe antes de tentar excluir
                    if (file_exists(public_path($networkFile))) {
                        unlink(public_path($networkFile));
                    }
                }
                
                Notification::make()
                    ->title('Rede Rádio deletada')
                    ->icon('heroicon-o-signal') 
                    ->body(''.$this->authUser->name.' deletou a rede '.$record['name'].'.')
                    ->sendToDatabase($this->recipients);
            }),
        ];
    }

    public function __construct()
    {
        $this->authUser = auth()->user();
        $this->recipients = User::all();
        $this->configurations = Configuration::all();
    }

    protected function handleRecordUpdate(Model $record, array $data): Model
    {    
        $networkFile = $record->file;

        if($networkFile) {
            // Verificar se o arquivo existe antes de tentar excluir
            if (file_exists(public_path($networkFile))) {
                unlink(public_path($networkFile));
            }

            $data['file'] = '/drrs/Diagrama Rede Rádio '.$data['name'].' '.Carbon::now()->format('d.m.Y H\hi').'.pdf';

            // Alterar registro do banco de dados
            Pdf::loadView('drrs.generate-network-file', ['drrData' => $data, 'config' => $this->configurations])->save(public_path().'/storage'.$data['file'].'')->setPaper('a4', 'landscape')->stream('download.pdf');
        }

        $record->update($data);
        
        Notification::make()
            ->title('Rede Rádio modificada')
            ->icon('heroicon-o-signal') 
            ->body(''.$this->authUser->name.' alterou os dados da rede '.$data['name'].'.')
            ->actions([
                Action::make('Visualizar')
                    ->link()
                    ->url(NetworkResource::getUrl('edit', ['record' => $record]))
            ])
            ->sendToDatabase($this->recipients);

        return $record;
    }
}
