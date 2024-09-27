<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Oracle;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Doctrine\DBAL\Schema\Schema;
use Filament\Resources\Resource;
use Filament\Actions\RestoreAction;
use Filament\Forms\Components\Section;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\RichEditor;
use Filament\Tables\Actions\DeleteAction;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Filters\TrashedFilter;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\UpdateOracleResource\Pages;
use Rmsramos\Activitylog\Actions\ActivityLogTimelineTableAction;
use App\Filament\Resources\UpdateOracleResource\RelationManagers;

class UpdateOracleResource extends Resource
{
    protected static ?string $model = Oracle::class;

    protected static bool $shouldRegisterNavigation = false;

    protected static ?string $modelLabel = 'Oráculo';

    protected static ?string $pluralModelLabel = 'Atualizar o Oráculo';

    protected static ?string $title = 'Atualizar o Oráculo';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make()
                    ->heading('Cadastrar Nova Pergunta e Resposta')
                    ->description('Atualize a base de conhecimento do Oráculo para torná-lo cada vez mais inteligente e preciso')
                    ->schema([
                        TextInput::make('question')
                            ->required()
                            ->label('Pergunta')
                            ->minLength(15),
                        RichEditor::make('answer')
                            ->required()
                            ->label('Resposta')
                            ->minLength(15),
                    ])
                
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->heading('Perguntas x Respostas')
            ->poll('10s')
            ->query(
                Oracle::query()
            )
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
                EditAction::make(),
                DeleteAction::make(),
                RestoreAction::make()
            ])
            ->bulkActions([
                // ...
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUpdateOracles::route('/'),
            'create' => Pages\CreateUpdateOracle::route('/create'),
            'edit' => Pages\EditUpdateOracle::route('/{record}/edit'),
        ];
    }
}
