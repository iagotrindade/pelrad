<?php

namespace App\Filament\Resources;

use Filament\Forms;
use App\Models\Loan;
use App\Models\User;
use Filament\Tables;
use App\Models\Material;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Tables\Filters\Filter;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Wizard;
use Filament\Tables\Columns\IconColumn;
use Illuminate\Support\Facades\Storage;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Forms\Components\DatePicker;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\Wizard\Step;
use App\Filament\Resources\LoanResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\LoanResource\RelationManagers;
use Rmsramos\Activitylog\Actions\ActivityLogTimelineAction;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\RestoreBulkAction;

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
                Select::make('status')
                    ->label('Status')
                    ->options([
                        'Aberta' => 'Aberta',
                        'Fechada' => 'Fechada'
                    ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('from')
                    ->searchable()
                    ->label('De:'),
                Tables\Columns\TextColumn::make('to')
                    ->searchable()
                    ->label('Para:'),
                Tables\Columns\TextColumn::make('status')
                    ->searchable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Criado em:')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Atualizado em:')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('download')
                    ->label('PDF')
                    ->url(fn (Loan $record): string => 'http://filament-app.test/'.$record->file.'')
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

                Tables\Filters\TrashedFilter::make()
            ])

            ->actions([
                ActivityLogTimelineAction::make('Logs'),
                Tables\Actions\DeleteAction::make()->before(function ($record) {
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
            'edit' => Pages\EditLoan::route('/{record}/edit'),
        ];
    }
}
