<?php

namespace App\Filament\Resources\UpdateOracleResource\Pages;

use App\Models\User;
use Filament\Actions;
use Illuminate\Database\Eloquent\Model;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;
use App\Filament\Resources\UpdateOracleResource;

class EditUpdateOracle extends EditRecord
{
    protected static string $resource = UpdateOracleResource::class;

    public $authUser;
    public $recipients;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    public function __construct()
    {
        $this->authUser = auth()->user();
        $this->recipients = User::all();
    }

    protected function handleRecordUpdate(Model $record, array $data): Model
    {    
        $record->update($data);

        Notification::make()
            ->title('Conhecimento alterado na base da dados do Oráculo')
            ->icon('heroicon-o-chat-bubble-left-right') 
            ->body($this->authUser->name . ' alterou a pergunta (' . "$record->question" . ') junto com sua resposta.')
        ->sendToDatabase($this->recipients);

        return $record;
    }
}
