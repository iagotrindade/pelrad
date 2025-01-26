<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Models\User;
use Filament\Actions;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use App\Filament\Resources\UserResource;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;
use Filament\Notifications\Actions\Action;

class EditUser extends EditRecord
{
    protected static string $resource = UserResource::class;

    public $authUser;
    public $recipients;

    public function __construct()
    {
        $this->authUser = Auth::user();
        $this->recipients = User::all();
    }

    protected function handleRecordUpdate(Model $record, array $data): Model
    {
        if (!empty($record->avatar) && $data['avatar'] !== $record->avatar) {
            $filePath = public_path('storage/' . $record->avatar);
        
            if (file_exists($filePath) && is_file($filePath)) {
                unlink($filePath);
            }
        }

        $record->update($data);

        Notification::make()
            ->title('Usuário modificado')
            ->icon('heroicon-o-user-group')
            ->body(''.$this->authUser->name.' alterou os dados do usuário '.$data['name'].'.')
            ->actions([
                Action::make('Visualizar')
                    ->link()
                    ->url(UserResource::getUrl('edit', ['record' => $record]))
            ])
            ->sendToDatabase($this->recipients);

        return $record;
    }


    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make()->after(function ($record) {
                $userImage = 'storage/'.$record->avatar.'';
                
                if($userImage) {
                    // Verificar se o arquivo existe antes de tentar excluir
                    if (file_exists(public_path($userImage))) {
                        unlink(public_path($userImage));
                    }
                }

                Notification::make()
                    ->title('Usuário deletado')
                    ->icon('heroicon-o-user-group')
                    ->body($this->authUser->name . ' deletou o usuário ' . $record->name . '.')
                ->sendToDatabase($this->recipients);
            }),
        ];
    }
}
