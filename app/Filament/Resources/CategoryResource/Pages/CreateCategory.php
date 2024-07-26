<?php

namespace App\Filament\Resources\CategoryResource\Pages;

use App\Models\User;
use Filament\Actions;
use Illuminate\Database\Eloquent\Model;
use Filament\Notifications\Notification;
use Filament\Notifications\Actions\Action;
use Filament\Resources\Pages\CreateRecord;
use App\Filament\Resources\CategoryResource;

class CreateCategory extends CreateRecord
{
    protected static string $resource = CategoryResource::class;

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
                    ->url(CategoryResource::getUrl('edit', ['record' => $record]))
            ])
            ->sendToDatabase($recipients);

        return $record;
    }
}
