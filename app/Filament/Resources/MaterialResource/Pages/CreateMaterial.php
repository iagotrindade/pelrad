<?php

namespace App\Filament\Resources\MaterialResource\Pages;


use App\Models\User;
use Filament\Actions;
use Pages\EditMaterial;
use App\Models\Material;
use App\Models\Component;
use Illuminate\Database\Eloquent\Model;
use Filament\Notifications\Notification;
use Filament\Notifications\Actions\Action;
use Filament\Resources\Pages\CreateRecord;
use App\Filament\Resources\MaterialResource;
use App\Filament\Resources\MaterialResource\Pages;

class CreateMaterial extends CreateRecord
{
    protected static string $resource = MaterialResource::class;

    protected function handleRecordCreation(array $data): Model
    {
        $authUser = auth()->user();
        $recipients = User::all();
        
        $record = static::getModel()::create($data);
 
        Notification::make()
            ->title('Material cadastrado')
            ->icon('heroicon-o-cube') 
            ->body(''.$authUser->name.' cadastrou o material '.$data['name'].'.')
            ->actions([
                Action::make('Visualizar')
                    ->link()
                    ->url(MaterialResource::getUrl('edit', ['record' => $record]))
            ])
            ->sendToDatabase($recipients);

        return $record;
    }
}
