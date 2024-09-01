<?php

namespace App\Filament\Resources;

use Filament\Forms;
use App\Models\User;
use Filament\Tables;
use App\Models\Category;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Repeater;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ViewColumn;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\CategoryResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Rmsramos\Activitylog\Actions\ActivityLogTimelineAction;
use App\Filament\Resources\CategoryResource\RelationManagers;
use Filament\Tables\Columns\ToggleColumn;

class CategoryResource extends Resource
{
    protected static ?string $model = Category::class;

    protected static ?string $navigationIcon = 'heroicon-o-tag';

    protected static ?string $navigationLabel = 'Categorias';

    protected static ?string $modelLabel = 'Categorias';

    protected static ?string $recordTitleAttribute = 'name';

    protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Categoria de Material')
                    ->description('As categorias serão usadas para agrupar os materiais no pronto')
                    ->schema([
                        TextInput::make('name')
                            ->label('Nome')
                            ->required(),

                        Toggle::make('show_compliance')
                            ->label('Mostrar no Pronto')
                            ->default(true)
                    ])
                    ->columns(1),

                    Section::make()
                        ->schema([
                            Repeater::make('components')
                                ->relationship('components') // Define o relacionamento
                                ->schema([
                                    Hidden::make('id'), // Campo oculto para o ID do componente
                                    TextInput::make('name')
                                        ->required()
                                        ->label('Nome'),

                                    TextInput::make('serial_number')
                                        ->label('Nr de Serie')
                                        ->required(),

                                    TextInput::make('code_number')
                                        ->required()
                                        ->label('Código do Componente'),

                                    TextInput::make('quantity')
                                        ->required()
                                        ->numeric()
                                        ->label('Quantidade')
                                        ->default(1),
                                ])
                                ->columns(4)
                                ->label('Componentes')
                                ->collapsible()
                                ->defaultItems(0)
                        ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Nome')
                    ->searchable(),
                TextColumn::make('materials_count')->counts('materials')
                    ->label('Materiais'),
                ToggleColumn::make('show_compliance')
                    ->label('Mostrar no Pronto'),
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
                
            ])
            ->actions([
                ActivityLogTimelineAction::make('Logs'),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make()->after(function ($record) {
                    $authUser = auth()->user();
                    $recipients = User::all();

                    Notification::make()
                        ->title('Categoria deletada')
                        ->icon('heroicon-o-tag')
                        ->body($authUser->name . ' deletou a categoria ' . $record->name . '.')
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
            'index' => Pages\ListCategory::route('/'),
            'create' => Pages\CreateCategory::route('/create'),
            'edit' => Pages\EditCategory::route('/{record}/edit'),
        ];
    }
}
