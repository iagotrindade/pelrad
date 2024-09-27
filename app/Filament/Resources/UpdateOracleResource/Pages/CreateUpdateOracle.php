<?php

namespace App\Filament\Resources\UpdateOracleResource\Pages;

use App\Models\User;
use Filament\Notifications\Actions\Action;
use Illuminate\Database\Eloquent\Model;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;
use App\Filament\Resources\UpdateOracleResource;

class CreateUpdateOracle extends CreateRecord
{
    protected static string $resource = UpdateOracleResource::class;

    protected static ?string $title = 'Atualizar o Antigão';

    protected function getFooterWidgets(): array
    {
        return [
            UpdateOracleResource\Widgets\OracleKnowledge::class,
        ];
    }

    protected function handleRecordCreation(array $data): Model
    {
        $authUser = auth()->user();
        $recipients = User::all();

        $record = static::getModel()::create($data);
 
        Notification::make()
            ->title('Conhecimento adicionado na base da dados do Oráculo')
            ->icon('heroicon-o-chat-bubble-left-right') 
            ->body(''.$authUser->name.' criou a pergunta ('.$data['question'].') junto com sua resposta.')
            ->actions([
                Action::make('Visualizar')
                    ->link()
                    ->url(UpdateOracleResource::getUrl('edit', ['record' => $record]))
            ])
            ->sendToDatabase($recipients);

        return $record;
    }
}
