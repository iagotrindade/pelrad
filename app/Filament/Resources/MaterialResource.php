<?php

namespace App\Filament\Resources;

use Filament\Forms;
use App\Models\User;
use Filament\Tables;
use App\Models\Category;
use App\Models\Material;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Actions\RestoreAction;
use Filament\Tables\Filters\Filter;
use Filament\Forms\Components\Select;
use Filament\Support\Enums\FontWeight;
use Filament\Forms\Components\Repeater;
use Filament\Notifications\Notification;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Tables\Actions\ExportAction;
use Filament\Tables\Columns\Layout\Stack;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Exports\MaterialExporter;
use Filament\Notifications\Actions\Action;
use App\Filament\Resources\MaterialResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Rmsramos\Activitylog\Actions\ActivityLogTimelineAction;
use App\Filament\Resources\MaterialResource\RelationManagers;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\RestoreBulkAction;
use Illuminate\Validation\ValidationException;
use Filament\Resources\Components\Tab;

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
                                ->imageEditor(),
                    ]),
                Forms\Components\Section::make()
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->label('Nome')
                            ->required(),

                        Select::make('categories_id')
                            ->label('Categoria')
                            ->searchable()
                            ->options(Category::all()->pluck('name', 'id')),

                        Forms\Components\TextInput::make('serial_number')
                            ->label('Nr de Serie')
                            ->required(),

                        Forms\Components\TextArea::make('description')
                            ->label('Descrição')
                            ->required(),

                        Forms\Components\TextInput::make('record_number')
                            ->label('Nr da Ficha'),

                        Forms\Components\TextInput::make('patrimony_number')
                            ->label('Nr de Patrimônio'),

                        Forms\Components\TextInput::make('patrimony_value')
                            ->label('Valor de Patrimônio'),

                        Forms\Components\TextInput::make('inclusion_document')
                            ->label('Boletim de Inclusão'),

                        Forms\Components\DatePicker::make('inclusion_date')
                            ->label('Data de Inclusão'),

                        Forms\Components\Select::make('status')
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
                ActivityLogTimelineAction::make('Logs'),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make()->before(function ($record) {
                    $authUser = auth()->user();
                    $recipients = User::all();

                    if($record->components->isNotEmpty()) {
                        return;
                    }

                    Notification::make()
                        ->title('Material deletado')
                        ->icon('heroicon-o-cube') 
                        ->body($authUser->name . ' deletou o material ' . $record->name . '.')
                    ->sendToDatabase($recipients);
                }),

                Tables\Actions\RestoreAction::make(),
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
