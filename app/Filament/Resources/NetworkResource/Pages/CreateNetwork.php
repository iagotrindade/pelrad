<?php

namespace App\Filament\Resources\NetworkResource\Pages;

use App\Models\User;
use Filament\Actions;
use App\Models\Network;
use Illuminate\Support\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Database\Eloquent\Model;
use Filament\Notifications\Notification;
use Filament\Notifications\Actions\Action;
use Filament\Resources\Pages\CreateRecord;
use App\Filament\Resources\NetworkResource;
use App\Models\Configuration;

class CreateNetwork extends CreateRecord
{
    protected static string $resource = NetworkResource::class;
    
    protected function handleRecordCreation(array $data): Model
    {
        $authUser = auth()->user();
        $recipients = User::all();
        $configurations = Configuration::all();

        $data['file'] = '/drrs/Diagrama Rede Rádio '.$data['name'].' '.Carbon::now()->format('d.m.Y H\hi').'.pdf';

        Pdf::loadView('drrs.generate-network-file', ['drrData' => $data, 'config' => $configurations])->save(public_path().'/storage'.$data['file'].'')->setPaper('a4', 'landscape')->stream('download.pdf');

        $record = static::getModel()::create($data);
 
        Notification::make()
            ->title('Rede Rádio criada')
            ->icon('heroicon-o-signal') 
            ->body(''.$authUser->name.' criou a rede '.$data['name'].'.')
            ->actions([
                Action::make('Visualizar')
                    ->link()
                    ->url(NetworkResource::getUrl('edit', ['record' => $record]))
            ])
            ->sendToDatabase($recipients);

        return $record;
    }
}
