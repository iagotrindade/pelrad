<?php

namespace App\Filament\Resources;

use Filament\Forms;
use App\Models\User;
use Filament\Tables;
use App\Models\Category;
use App\Models\Material;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Support\RawJs;
use Filament\Resources\Resource;
use Filament\Tables\Filters\Filter;
use Illuminate\Support\Facades\Auth;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Forms\Components\DatePicker;
use Filament\Tables\Actions\ExportAction;
use Filament\Tables\Actions\ImportAction;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Exports\MaterialExporter;
use App\Filament\Imports\MaterialImporter;
use App\Filament\Resources\MaterialResource\Pages;
use Rmsramos\Activitylog\Actions\ActivityLogTimelineTableAction;


class MaterialResource extends Resource
{
    protected static ?string $model = Material::class;

    protected static ?string $navigationIcon = 'heroicon-o-cube';

    protected static ?string $navigationLabel = 'Carga';

    protected static ?string $modelLabel = 'Materiais';

    protected static ?string $recordTitleAttribute = 'name';

    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make()
                    ->schema([
                        Forms\Components\FileUpload::make('images')
                            ->image()
                            ->label('Imagens')
                            ->multiple()
                            ->reorderable()
                            ->maxFiles(5)
                            ->imageEditor()
                            ->directory('materials')
                            ->panelLayout('grid'),
                    ]),
                Forms\Components\Section::make()
                    ->schema([
                        TextInput::make('name')
                            ->label('Nome')
                            ->required(),

                        Select::make('categories_id')
                            ->label('Categoria')
                            ->searchable()
                            ->options(Category::all()->pluck('name', 'id')),

                        TextInput::make('serial_number')
                            ->label('Nr de Serie')
                            ->required(),

                        TextArea::make('description')
                            ->label('Descrição'),

                        TextInput::make('record_number')
                            ->label('Nr da Ficha'),

                        TextInput::make('patrimony_number')
                            ->label('Nr de Patrimônio'),
      
                        TextInput::make('patrimony_value')
                            ->mask(RawJs::make('$money($input)'))
                            ->label('Valor de Patrimônio')
                            ->stripCharacters(','),

                        TextInput::make('inclusion_document')
                            ->label('Boletim de Inclusão'),

                        DatePicker::make('inclusion_date')
                            ->label('Data de Inclusão'),

                        Select::make('status')
                            ->options([
                                'Disponível' => 'Disponível',
                                'Indisponível' => 'Indisponível',
                                'Cautelado' => 'Cautelado',
                                'Manutenção' => 'Manutenção',
                                'Descarregado' => 'Descarregado',
                            ])
                            ->required()
                            ->validationMessages([
                                'status' => 'Não é possível alterar o :attribute por que ele está em manutenção ou cautelado.',
                            ])
                            ->default('Disponível'),
                    ])->columns(3),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->headerActions([
                ImportAction::make()
                    ->importer(MaterialImporter::class),
                ExportAction::make()
                    ->exporter(MaterialExporter::class),
            ])
            ->columns([
                Tables\Columns\ImageColumn::make('images')
                    ->circular()
                    ->label('Imagens'),

                Tables\Columns\TextColumn::make('serial_number')
                    ->searchable()
                    ->label('Nr de Serie'),

                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->label('Nome')
                    ->limit(30),

                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'Disponível' => 'success',
                        'Indisponível' => 'danger',
                        'Cautelado' => 'warning',
                        'Manutenção' => 'warning',
                        'Descarregado' => 'danger',
                })
            ])

            ->filters([
                Filter::make('data')
                    ->form([
                        DatePicker::make('Última atualização'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['Última atualização'],
                                fn (Builder $query, $date): Builder => $query->whereDate('updated_at', '>=', $date),
                            );
                }),

                Filter::make('inclusion')
                    ->form([
                        DatePicker::make('Inclusão em carga'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['Inclusão em carga'],
                                fn (Builder $query, $date): Builder => $query->where('inclusion_date', '>=', $date),
                            );
                }),
                
                Tables\Filters\TrashedFilter::make()
            ])

            ->actions([
                Tables\Actions\ViewAction::make(),
                ActivityLogTimelineTableAction::make('Logs'),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make()->before(function ($record) {
                    $authUser = Auth::user();
                    $recipients = User::all();

                    if($record->components && $record->components->isNotEmpty()) {
                        return;
                    }

                    Notification::make()
                        ->title('Material deletado')
                        ->icon('heroicon-o-cube') 
                        ->body($authUser->name . ' deletou o material ' . $record->name . '.')
                    ->sendToDatabase($recipients);
                }),

                Tables\Actions\RestoreAction::make()->before(function ($record) {
                    $authUser = Auth::user();
                    $recipients = User::all();

                    Notification::make()
                        ->title('Material restaurado')
                        ->icon('heroicon-o-cube') 
                        ->body($authUser->name . ' restaurou o material ' . $record->name . '.')
                    ->sendToDatabase($recipients);
                }),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\RestoreBulkAction::make()
                ]),
            ]);
    }

    public static function getGloballySearchableAttributes(): array
    {
        return ['name', 'serial_number'];
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
            'index' => Pages\ListMaterials::route('/'),
            'create' => Pages\CreateMaterial::route('/create'),
            'edit' => Pages\EditMaterial::route('/{record}/edit'),
        ];
    }
}
