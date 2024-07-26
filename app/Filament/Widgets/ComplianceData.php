<?php

namespace App\Filament\Widgets;

use Filament\Tables;
use App\Models\Compliance;
use Filament\Tables\Table;
use Filament\Tables\Actions\Action;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\Layout\Stack;
use Filament\Widgets\TableWidget as BaseWidget;
use Rmsramos\Activitylog\Actions\ActivityLogTimelineAction;
use Filament\Tables\Filters\Filter;
use Filament\Forms\Components\DatePicker;
use Illuminate\Database\Eloquent\Builder;

class ComplianceData extends BaseWidget
{

    protected static ?int $sort = 3;
    protected int | string | array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Compliance::query()
            )
            ->poll('10s')
            ->heading('Prontos do Pelotão')
            ->headerActions([
                Action::make('generate')
                    ->label('Gerar Pronto')
                    ->url(fn (): string => route('report.compliance'), shouldOpenInNewTab: true)
            ])
            ->columns([
                Stack::make([
                    Tables\Columns\TextColumn::make('name')
                    ->label('Nome')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('download')
                    ->label('PDF')
                    ->url(fn (Compliance $record): string => 'http://filament-app.test/'.$record->file.'')
                    ->default('Download')
                    ->icon('heroicon-m-arrow-down-tray')
                    ->openUrlInNewTab(),
                ])
            
                
            ])->contentGrid([
                'md' => 3,
                'xl' => 4,
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
                
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->emptyStateHeading('Nenhum pronto encontrado');
    }
}
