<?php

namespace App\Filament\Resources;

use Filament\Forms;
use App\Models\User;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Section;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Tables\Columns\ImageColumn;
use Filament\Forms\Components\FileUpload;
use Filament\Tables\Columns\Layout\Split;
use Illuminate\Database\Eloquent\Builder;
use Filament\Notifications\Actions\Action;
use App\Filament\Resources\UserResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\UserResource\RelationManagers;
use Rmsramos\Activitylog\Actions\ActivityLogTimelineAction;



class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-group';

    protected static ?string $navigationLabel = 'Usuários';

    protected static ?string $modelLabel = 'Usuários';

    protected static ?string $recordTitleAttribute = 'name';

    protected static ?int $navigationSort = 6;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make()
                ->schema([
                    FileUpload::make('avatar')
                        ->imageEditor()
                        ->imageEditorAspectRatios([
                            '16:9',
                            '4:3',
                            '1:1',
                        ])
                        ->circleCropper(),
                        ]),
                Forms\Components\Section::make()
                ->schema([
                    Select::make('graduation')
                    ->required()
                    ->label('Graduação')
                    ->live()
                    ->options([
                        'Sd' => 'Soldado',
                        'Cb' => 'Cabo',
                        '3º Sgt' => '3º SGT',
                        '2º Sgt' => '2º SGT',
                        '1º Sgt' => '1º SGT',
                        'Sub' => 'Subtenente',
                        '2º Ten' => '2º Tenente',
                        '1º Ten' => '1º Tenente',
                        'Cap' => 'Capitão',
                        'Major' => 'Major',
                        'Ten Cel' => 'Tenente Coronel',
                        'Cel' => 'Coronel',
                    ]),
                    
                    TextInput::make('name')
                    ->required(),

                    TextInput::make('email')
                        ->email()
                        ->required(),

                    TextInput::make('password')
                        ->password()
                        ->required(),
                ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('avatar')
                    ->circular(),
                TextColumn::make('graduation')
                    ->searchable()
                    ->sortable()
                    ->label('Graduação'),
                TextColumn::make('name')
                    ->searchable()
                    ->sortable()
                    ->label('Nome'),

                TextColumn::make('email')
                    ->searchable()
                    ->sortable()
                    ->label('Email'),

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
                        ->title('Usuário deletado')
                        ->icon('heroicon-o-user-group')
                        ->body($authUser->name . ' deletou o usuário ' . $record->name . '.')
                    ->sendToDatabase($recipients);
                }),
            
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getGloballySearchableAttributes(): array
    {
        return ['name', 'email'];
    }

    public static function getRelations(): array
    {
        return [
            
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}
