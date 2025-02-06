<?php

namespace App\Filament\Resources\CategoryResource\Pages;

use Filament\Actions;
use Illuminate\Database\Eloquent\Model;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;
use Filament\Notifications\Actions\Action;
use App\Filament\Resources\CategoryResource;
use App\Models\User;

class EditCategory extends EditRecord
{
    protected static string $resource = CategoryResource::class;

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
                $record->components()->delete();

                Notification::make()
                    ->title('Categoria deletada')
                    ->icon('heroicon-o-tag')
                    ->body($this->authUser->name . ' deletou a categoria ' . $record->name . '.')
                ->sendToDatabase($this->recipients);
            }),
        ];
    }

    protected function handleRecordUpdate(Model $record, array $data): Model
    {
        $record->update($data);

        Notification::make()
            ->title('Categoria modificada')
            ->icon('heroicon-o-tag')
            ->body(''.$this->authUser->name.' alterou os dados da categoria '.$data['name'].'.')
            ->actions([
                Action::make('Visualizar')
                    ->link()
                    ->url(CategoryResource::getUrl('edit', ['record' => $record]))
            ])
            ->sendToDatabase($this->recipients);

        return $record;
    }
}
