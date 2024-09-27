<?php

namespace App\Filament\Resources;

use Filament\Forms;
use App\Models\User;
use Filament\Tables;
use App\Models\Network;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Repeater;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Tables\Actions\DeleteAction;
use Illuminate\Database\Eloquent\Builder;
use Filament\Infolists\Components\TextEntry;
use App\Filament\Resources\NetworkResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Rmsramos\Activitylog\Actions\ActivityLogTimelineAction;
use App\Filament\Resources\NetworkResource\RelationManagers;
use Rmsramos\Activitylog\Actions\ActivityLogTimelineTableAction;
use Joaopaulolndev\FilamentPdfViewer\Infolists\Components\PdfViewerEntry;

class NetworkResource extends Resource
{
    protected static ?string $model = Network::class;

    protected static ?string $navigationLabel = 'Redes Rádio';

    protected static ?string $modelLabel = 'Redes';

    protected static ?string $recordTitleAttribute = 'name';

    protected static ?int $navigationSort = 4;

    protected static ?string $navigationIcon = 'heroicon-o-signal';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make()
                    ->schema([
                        TextInput::make('name')
                        ->label('Nome da Rede')
                        ->required(),
                    TextInput::make('frequency')
                        ->label('Frequência')
                        ->required(),
                    TextInput::make('alternative_frequency')
                        ->label('Frequência Alternativa')
                        ->required(),
                    TextInput::make('drr_quantity')
                        ->label('Quantidade de Quadros de Rede')
                        ->numeric()
                        ->required(),
                    ])->columns(2),

                Section::make()
                    ->schema([
                        Repeater::make('stations_data')
                            ->schema([
                            TextInput::make('station_name')
                                ->required()
                                ->label('Nome do Posto'),

                            TextInput::make('responsible')
                                ->label('Responsável')
                                ->required(),

                            TextInput::make('phone')
                                ->mask('(99) 9-9999-9999')
                                ->required()
                                ->label('Contato')
                                ->length(16),
                                

                            TextInput::make('radop')
                                ->label('Radioperador')
                                ->required(), 
                        ])
                        ->columns(4)
                        ->label('Postos')
                        ->collapsible()
                        ->defaultItems(3)
                        ->columns(2)
                ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
        ->columns([
            TextColumn::make('name')
                ->searchable()
                ->label('Nome'),
            TextColumn::make('frequency')
                ->searchable()
                ->label('Frequência'),
            TextColumn::make('alternative_frequency')
                ->label('Frequência Alternativa')
                ->searchable(),
            TextColumn::make('created_at')
                ->dateTime()
                ->label('Criado em')
                ->formatStateUsing(function ($state) {
                    return \Carbon\Carbon::parse($state)->translatedFormat('d M Y \à\s H:i');
                })
                ->searchable()
                ->sortable(),
            TextColumn::make('download')
                ->label('PDF')
                ->url(fn (Network $record): string => 'http://filament-app.test/'.$record->file.'')
                ->default('Download')
                ->icon('heroicon-m-arrow-down-tray')
                ->openUrlInNewTab()
        ])
            ->filters([
                //
            ])
            ->actions([
                ViewAction::make(),
                ActivityLogTimelineTableAction::make('Logs'),
                EditAction::make(),
                DeleteAction::make()->before(function ($record) {
                    $authUser = auth()->user();
                    $recipients = User::all();
            
                    $networkFile = 'storage/'.$record->file.'';
            
                    if($networkFile) {
                        // Verificar se o arquivo existe antes de tentar excluir
                        if (file_exists(public_path($networkFile))) {
                            unlink(public_path($networkFile));
                        }
                    }
                    
                    Notification::make()
                        ->title('Rede Rádio deletada')
                        ->icon('heroicon-o-signal') 
                        ->body(''.$authUser->name.' deletou a rede '.$record['name'].'.')
                        ->sendToDatabase($recipients);
                }),
            ])
            ->bulkActions([

            ]);
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                \Filament\Infolists\Components\Section::make('Dados da Rede')
                    ->description('Esses dados são os preenchidos no ato da criação da rede')
                    ->schema([
                        TextEntry::make('name')
                            ->label('Nome da Rede'),
                        TextEntry::make('frequency')
                            ->label('Frequência'),
                        TextEntry::make('alternative_frequency')
                            ->label('Frequência Alternativa'),
                        TextEntry::make('created_at')
                            ->label('Data de Criação')
                            ->formatStateUsing(function ($state) {
                                return \Carbon\Carbon::parse($state)->translatedFormat('d M Y');
                            }),   
                    ])->columns(4),
                \Filament\Infolists\Components\Section::make('Quadros Rede Rádio')
                    ->description('Documento gerado contendo os Quadro de Rede Rádio')
                    ->collapsible()
                    ->schema([
                        PdfViewerEntry::make('file')
                            ->label('')
                            ->minHeight('120svh'),
                    ]),  
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
            'index' => Pages\ListNetworks::route('/'),
            'create' => Pages\CreateNetwork::route('/create'),
            'view' => Pages\ViewNetwork::route('/{record}/view'),
            'edit' => Pages\EditNetwork::route('/{record}/edit'),
        ];
    }
}
