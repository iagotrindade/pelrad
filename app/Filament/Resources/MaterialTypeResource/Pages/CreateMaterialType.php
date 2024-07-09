<?php

namespace App\Filament\Resources\MaterialTypeResource\Pages;

use App\Models\User;
use Filament\Actions;
use Illuminate\Database\Eloquent\Model;
use Filament\Notifications\Notification;
use Filament\Notifications\Actions\Action;
use Filament\Resources\Pages\CreateRecord;
use App\Filament\Resources\MaterialTypeResource;

class CreateMaterialType extends CreateRecord
{
    protected static string $resource = MaterialTypeResource::class;

    protected function handleRecordCreation(array $data): Model
    {
        $authUser = auth()->user();
        $recipients = User::all();

        $record = static::getModel()::create($data);

        Notification::make()
            ->title('Categoria cadastrada')
            ->icon('heroicon-o-tag')
            ->body(''.$authUser->name.' cadastrou a categoria '.$data['name'].'.')
            ->actions([
                Action::make('Visualizar')
                    ->link()
                    ->url(MaterialTypeResource::getUrl('edit', ['record' => $record]))
            ])
            ->sendToDatabase($recipients);

        return $record;
    }
}
