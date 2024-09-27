<?php

namespace App\Filament\Resources\UpdateOracleResource\Widgets;

use App\Models\User;
use Filament\Tables;
use App\Models\Oracle;
use Filament\Tables\Table;
use Filament\Tables\Actions\Action;
use Filament\Tables\Columns\TextColumn;
use Filament\Notifications\Notification;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Actions\Action as ActionsAction;
use Filament\Widgets\TableWidget as BaseWidget;
use Rmsramos\Activitylog\Actions\ActivityLogTimelineTableAction;

class OracleKnowledge extends BaseWidget
{
    protected int | string | array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->heading('Perguntas X Respostas')
            ->poll('10s')
            ->query(
                Oracle::query()
            )
            ->headerActions([
                Action::make('orientation')
                    ->label('Ver Orientações')
                    ->url('http://filament-app.test/storage/oracle/repository/orientation.pdf', shouldOpenInNewTab: true)
            ])
            ->columns([
                TextColumn::make('question')
                    ->label('Pergunta')
                    ->limit(60)
                    ->searchable()
                    ->sortable(),
                TextColumn::make('answer')
                    ->label('Resposta')
                    ->limit(60)
                    ->searchable()
                    ->sortable()
                    ->html(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->label('Criado em')
                    ->formatStateUsing(function ($state) {
                        return \Carbon\Carbon::parse($state)->translatedFormat('d M Y \à\s H:i');
                    })
                    ->searchable()
                    ->sortable()
            ])
            ->filters([
                TrashedFilter::make()
            ])
            ->actions([
                ActivityLogTimelineTableAction::make('Logs'),
                Action::make('Editar')
                    ->icon('heroicon-o-pencil-square')
                    ->url(fn (Oracle $record): string => ''.$record->id.'/edit'),
                Tables\Actions\DeleteAction::make()->before(function ($record) {
                    $authUser = auth()->user();
                    $recipients = User::all();

                    Notification::make()
                        ->title('Conhecimento deletado da base da dados do Oráculo')
                        ->icon('heroicon-o-chat-bubble-left-right') 
                        ->body($authUser->name . ' deletou a pergunta (' . $record->question . ') junto com sua resposta.')
                    ->sendToDatabase($recipients);
                }),
                Tables\Actions\RestoreAction::make()->before(function ($record) {
                    $authUser = auth()->user();
                    $recipients = User::all();

                    Notification::make()
                        ->title('Conhecimento restaurado na base da dados do Oráculo')
                        ->icon('heroicon-o-chat-bubble-left-right') 
                        ->body($authUser->name . ' restaurou a pergunta (' . $record->question . ') junto com sua resposta.')
                    ->sendToDatabase($recipients);
                }),
            ])
            ->bulkActions([
                // ...
            ]);
    }
}
