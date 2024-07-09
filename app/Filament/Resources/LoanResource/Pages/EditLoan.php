<?php

namespace App\Filament\Resources\LoanResource\Pages;

use App\Models\User;
use Filament\Actions;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use App\Filament\Resources\LoanResource;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;
use Filament\Notifications\Actions\Action;

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
        $record->update($data);

        Notification::make()
            ->title('Cautela modificada')
            ->icon('heroicon-o-rectangle-stack') 
            ->body(''.$this->authUser->name.' alterou o status da cautela do '.$record->to.' para '.$data['status'].'')
            ->actions([
                Action::make('Visualizar')
                    ->link()
                    ->url(LoanResource::getUrl('edit', ['record' => $record]))
            ])
            ->sendToDatabase($this->recipients);

        return $record;
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
