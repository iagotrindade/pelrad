<?php

namespace App\Filament\Resources;

use Filament\Forms;
use App\Models\User;
use Filament\Tables;
use App\Models\Material;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\Maintenance;
use Filament\Resources\Resource;
use Filament\Tables\Columns\Column;
use Filament\Tables\Filters\Filter;
use Filament\Forms\Components\Split;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Support\Facades\Storage;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\MaintenanceResource\Pages;
use Rmsramos\Activitylog\Actions\ActivityLogTimelineAction;
use App\Filament\Resources\MaintenanceResource\RelationManagers;

class MaintenanceResource extends Resource
{
    protected static ?string $model = Maintenance::class;

    protected static ?string $navigationIcon = 'heroicon-o-wrench-screwdriver';

    protected static ?string $navigationLabel = 'Manutenção';

    protected static ?string $modelLabel = 'Manutenção';

    protected static ?string $pluralModelLabel = 'Manutenções';

    protected static ?string $recordTitleAttribute = 'description';

    protected static ?int $navigationSort = 4;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Materiais')
                    ->description('Selecione os materiais encaminhados a manutenção')
                    ->schema([
                        Select::make('materials')
                            ->required()
                            ->label('Materiais')
                            ->multiple()
                            ->getSearchResultsUsing(fn (string $search): array => Material::where('name', 'like', "%{$search}%")->limit(50)->pluck('name', 'id')->toArray())
                            ->getOptionLabelsUsing(fn (array $values): array => Material::whereIn('id', $values)->pluck('name', 'id')->toArray()),

                        MarkdownEditor::make('description')
                            ->required()
                            ->label('Descrição'),

                        TextInput::make('destiny')
                            ->required()
                            ->label('Destino'),

                        Select::make('status')
                            ->required()
                            ->label('Status')
                            ->options([
                                'Encerrada' => 'Encerrada',
                                'Em andamento' => 'Em andamento'
                            ])
                            ->default('Em andamento'),
                    ]),

                Section::make('Guia de Remessa')
                    ->description('Faça upload da Guia de Remessa gerada pelo S4 e outros arquivos caso necessário')
                    ->schema([                
                        FileUpload::make('file')
                            ->label('Arquivo')
                            ->directory('maintenance-files')
                            ->acceptedFileTypes(['application/pdf']),
                    ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('description')
                    ->label('Descrição')
                    ->searchable()
                    ->sortable()
                    ->limit(30),

                TextColumn::make('destiny')
                    ->label('Destino')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('download')
                    ->label('Arquivo')
                    ->url(fn (Maintenance $record): string => 'http://filament-app.test/'.$record->file.'')
                    ->default('Download')
                    ->icon('heroicon-m-arrow-down-tray')
                    ->limit(50)
                    ->openUrlInNewTab(),

                TextColumn::make('created_at')
                    ->dateTime()
                    ->label('Criado em')
                    ->formatStateUsing(function ($state) {
                        return \Carbon\Carbon::parse($state)->format('d M Y \à\s H:i');
                    })
                    ->searchable()
                    ->sortable(),

                TextColumn::make('status')
                    ->label('Status')
                    ->searchable()
                    ->sortable(),
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
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                ActivityLogTimelineAction::make('Logs'),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make()->before(function ($record) {
                    $authUser = auth()->user();
                    $recipients = User::all();

                    // Atualizar o status de cada material para 'Disponível'
                    $oldInfo = json_decode($record['old_info'], true);
                    // Verificar se a decodificação foi bem-sucedida e se materialsInfo é um array
                    if (is_array($record['materials'])) {
                        // Atualizar o status de cada material para 'Cautelado'
                        foreach ($record['materials'] as $key => $id) {
                            Material::where('id', $id)->update(['status' => $oldInfo[$key]['old_status']]); 
                        }
                    }

                    if (Storage::exists('public/'.$record->file)) {
                        Storage::delete('public/'.$record->file);
                    } 

                    Notification::make()
                        ->title('Manutenção deletada')
                        ->icon('heroicon-o-wrench-screwdriver') 
                        ->body($authUser->name . ' deletou a manutenção ' . $record->name . '.')
                    ->sendToDatabase($recipients);
                }),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
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
            'index' => Pages\ListMaintenances::route('/'),
            'create' => Pages\CreateMaintenance::route('/create'),
            'edit' => Pages\EditMaintenance::route('/{record}/edit'),
        ];
    }
}
