<?php

namespace App\Filament\Resources\ConfigurationResource\Pages;

use App\Models\User;
use Filament\Actions;
use Illuminate\Database\Eloquent\Model;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;
use Filament\Notifications\Actions\Action;
use App\Filament\Resources\ConfigurationResource;

class EditConfiguration extends EditRecord
{
    protected static string $resource = ConfigurationResource::class;

    protected function getHeaderActions(): array
    {
        return [

        ];
    }

    protected function handleRecordUpdate(Model $record, array $data): Model
    {
        $record->update($data);

        $authUser = auth()->user();
        $recipients = User::all();

        Notification::make()
            ->title('Configurações modificadas')
            ->icon('heroicon-o-cog-8-tooth')
            ->body(''.$authUser->name.' alterou as configurações das cautelas')
            ->actions([
                Action::make('Visualizar')
                    ->link()
                    ->url(ConfigurationResource::getUrl('edit', ['record' => $record]))
            ])
            ->sendToDatabase($recipients);

        return $record;
    }
}
