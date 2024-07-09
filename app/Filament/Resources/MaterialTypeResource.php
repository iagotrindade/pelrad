<?php

namespace App\Filament\Resources;

use Filament\Forms;
use App\Models\Type;
use App\Models\User;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\MaterialTypeResource\Pages;
use App\Filament\Resources\MaterialTypeResource\RelationManagers;

class MaterialTypeResource extends Resource
{
    protected static ?string $model = Type::class;

    protected static ?string $navigationIcon = 'heroicon-o-tag';

    protected static ?string $navigationLabel = 'Categorias';

    protected static ?string $modelLabel = 'Categorias';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Categoria de Material')
                    ->description('Essas categorias serão usadas para agrupar os materiais nas cautelas')
                    ->schema([
                        TextInput::make('name')
                        ->label('Nome')
                        ->required()
                    ])
                    ->columns(1),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Nome')
                    ->searchable(),
                // Add more columns as needed...
            ])
            ->filters([
                
            ])
            ->actions([
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
            'index' => Pages\ListMaterialTypes::route('/'),
            'create' => Pages\CreateMaterialType::route('/create'),
            'edit' => Pages\EditMaterialType::route('/{record}/edit'),
        ];
    }
}
