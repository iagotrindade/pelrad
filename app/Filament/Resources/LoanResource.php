<?php

namespace App\Filament\Resources;

use Filament\Forms;
use App\Models\Loan;
use App\Models\User;
use Filament\Tables;
use Filament\Infolists;
use App\Models\Material;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables\Filters\Filter;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Wizard;
use Filament\Forms\Components\Section;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Support\Facades\Storage;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\Wizard\Step;
use Filament\Tables\Actions\RestoreAction;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Infolists\Components\TextEntry;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Infolists\Components\ImageEntry;
use Filament\Tables\Actions\DeleteBulkAction;
use App\Filament\Resources\LoanResource\Pages;
use Filament\Tables\Actions\RestoreBulkAction;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\LoanResource\RelationManagers;
use Rmsramos\Activitylog\Actions\ActivityLogTimelineAction;
use Rmsramos\Activitylog\Actions\ActivityLogTimelineTableAction;
use Joaopaulolndev\FilamentPdfViewer\Forms\Components\PdfViewerField;
use Joaopaulolndev\FilamentPdfViewer\Infolists\Components\PdfViewerEntry;

class LoanResource extends Resource
{
    protected static ?string $model = Loan::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationLabel = 'Cautelas';

    protected static ?string $modelLabel = 'Cautelas';

    protected static ?string $recordTitleAttribute = 'to';

    protected static ?int $navigationSort = 1;

    public $selectedMaterial = [];

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Situação')
                    ->description('Altere a situação e anexe a cautela assinada')
                    ->schema([
                        Select::make('status')
                        ->label('Status')
                        ->options([
                            'Aberta' => 'Aberta',
                            'Fechada' => 'Fechada'
                        ]),
                        FileUpload::make('signed_file')
                            ->label('Cautela Assinada')
                            ->directory('loans')
                            ->acceptedFileTypes(['application/pdf']),
                        
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('to')
                    ->searchable()
                    ->label('OM:'),
                TextColumn::make('status')
                    ->searchable(),
                TextColumn::make('return_date')
                    ->dateTime()
                    ->label('Devolução em')
                    ->formatStateUsing(function ($state) {
                        return \Carbon\Carbon::parse($state)->translatedFormat('d M Y');
                    })
                    ->searchable()
                    ->sortable(),
                TextColumn::make('created_at')
                    ->label('Criado em:')
                    ->formatStateUsing(function ($state) {
                        return \Carbon\Carbon::parse($state)->translatedFormat('d M Y \à\s H:i');
                    })
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->label('Atualizado em:')
                    ->formatStateUsing(function ($state) {
                        return \Carbon\Carbon::parse($state)->translatedFormat('d M Y \à\s H:i');
                    })
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('download')
                    ->label('PDF')
                    ->url(fn (Loan $record): string => url('storage/'.$record->file))
                    ->default('Download')
                    ->icon('heroicon-m-arrow-down-tray')
                    ->openUrlInNewTab()
            ])

            ->filters([
                Filter::make('data')
                    ->form([
                        DatePicker::make('Data'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['Data'],
                                fn (Builder $query, $date): Builder => $query->whereDate('updated_at', '>=', $date),
                            );
                }),

                TrashedFilter::make()
            ])

            ->actions([
                ActivityLogTimelineTableAction::make('Logs'),
                ViewAction::make(),
                EditAction::make(),
                DeleteAction::make()->before(function ($record) {
                    $authUser = auth()->user();
                    $recipients = User::all();

                    $relativePath = str_replace('/storage', 'public', $record->file);

                    // Atualizar o status de cada material para 'Disponível'
                    $materialsInfo = json_decode($record['materials_info'], true);

                    // Verificar se a decodificação foi bem-sucedida e se materialsInfo é um array
                    if (is_array($materialsInfo)) {
                        // Atualizar o status de cada material para 'Cautelado'
                        foreach ($materialsInfo as $material) {
                            if (isset($material['id'])) {
                                // Supondo que você tenha um modelo Material para atualizar o status
                                Material::where('id', $material['id'])->update(['status' => 'Disponível']);
                            }
                        }
                    }

                    if (Storage::exists($relativePath)) {
                        Storage::delete($relativePath);
                    } 

                    Notification::make()
                        ->title('Cautela deletada')
                        ->icon('heroicon-o-rectangle-stack') 
                        ->body($authUser->name . ' deletou a cautela ' . $record->name . '.')
                    ->sendToDatabase($recipients);
                }),

                
                RestoreAction::make(),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    RestoreBulkAction::make()
                ]),
            ]);
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Infolists\Components\Section::make('Dados da Cautela')
                    ->description('Esses dados são os preenchidos no ato da cautela')
                    ->schema([
                        TextEntry::make('to')
                            ->label('Organização Militar'),
                        TextEntry::make('graduation')
                            ->label('Graduação'),
                        TextEntry::make('name')
                            ->label('Nome'),
                        TextEntry::make('idt')
                            ->label('Identindade'),
                        TextEntry::make('contact')
                            ->url(fn (Loan $record): string => 'https://wa.me/55'.str_replace(['(', ')', '-', ' '], '', $record->contact).'', shouldOpenInNewTab: true)
                            ->label('Contato'),
                        TextEntry::make('created_at')
                            ->label('Data da cautela')
                            ->formatStateUsing(function ($state) {
                                return \Carbon\Carbon::parse($state)->translatedFormat('d M Y');
                            }), 
                        TextEntry::make('return_date')
                            ->label('Previsão de retorno')
                            ->formatStateUsing(function ($state) {
                                return \Carbon\Carbon::parse($state)->translatedFormat('d M Y');
                            }),    
                        TextEntry::make('status')
                            ->label('Situação'),   
                    ])->columns(4),
                \Filament\Infolists\Components\Section::make('Cautela não Assinada')
                    ->description('PDF gerado na criação cautela')
                    ->collapsible()
                    ->schema([
                        PdfViewerEntry::make('file')
                            ->label('')
                            ->minHeight('80svh'),
                    ]),
                \Filament\Infolists\Components\Section::make('Cautela Assinada')
                    ->description('Inserida no sistema após assinatura')
                    ->collapsible()
                    ->schema([
                        PdfViewerEntry::make('signed_file')
                            ->label('')
                            ->minHeight('80svh')
                    ]),
                
            ]);
    }

    public static function getGloballySearchableAttributes(): array
    {
        return ['to', 'name', 'idt'];
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
            'index' => Pages\ListLoans::route('/'),
            'create' => Pages\CreateLoan::route('/create'),
            'view' => Pages\ViewLoan::route('/{record}'),
            'edit' => Pages\EditLoan::route('/{record}/edit'),
        ];
    }
}
