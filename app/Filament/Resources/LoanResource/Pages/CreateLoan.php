<?php

namespace App\Filament\Resources\LoanResource\Pages;

use Carbon\Carbon;
use App\Models\User;
use Filament\Actions;
use Filament\Forms\Get;
use Livewire\Component;
use App\Models\Material;
use App\Models\Configuration;
use Barryvdh\DomPDF\Facade\Pdf;
use Filament\Forms\Components\Button;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Wizard;
use Filament\Forms\Components\Repeater;
use Illuminate\Database\Eloquent\Model;
use App\Filament\Resources\LoanResource;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Wizard\Step;
use Filament\Notifications\Actions\Action;
use Filament\Resources\Pages\CreateRecord;
use Filament\Forms\Components\MarkdownEditor;

class CreateLoan extends CreateRecord
{
    use CreateRecord\Concerns\HasWizard;

    protected static string $resource = LoanResource::class;

    public $selectedMaterial = [];

    protected function handleRecordCreation(array $data): Model
    {
        

        $authUser = auth()->user();
        $recipients = User::all();

        $record = static::getModel()::create($data);

        

        Notification::make()
            ->title('Nova cautela gerada')
            ->icon('heroicon-o-rectangle-stack') 
            ->body(''.$authUser->name.' gerou uma nova cautela para '.$data['to'].'.')
            ->actions([
                Action::make('Visualizar')
                    ->link()
                    ->url(LoanResource::getUrl('edit', ['record' => $record]))
            ])
            ->sendToDatabase($recipients);

        return $record;
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $configuration = Configuration::find(1);

        $materialIds = [];
        foreach ($data['material_group'] as $value) {
            $materialIds = array_merge($materialIds, $value['materials']);
        }

        $materials = Material::whereIn('id', $materialIds)->get()->keyBy('id');

        // Inicializar array para armazenar as informações dos materiais
        $materialInfoArray = [];

        foreach ($data['material_group'] as $key => $value) {
            if ($value['qtd'] == null) {
                $value['qtd'] = count($value['materials']);     
            }
            foreach ($value['materials'] as $itemKey => $item) {
                $value['materials'][$itemKey] = $materials[$item] ?? null;
                
                // Adicionar informações do material ao array
                if (isset($materials[$item])) {
                    $materialInfoArray[] = $materials[$item];
                }
            }
            $data['material_group'][$key] = $value;
            $data['material_group'][$key]['groupName'] = $data['material_group'][0]['materials'][0]->name; 
            $data['material_group'][$key]['groupComponents'] = $data['material_group'][0]['materials'][0]->components;
        }

        // Converter o array de informações dos materiais para JSON e armazenar em material_info
        $data['materials_info'] = json_encode($materialInfoArray);

        $data['from'] = $configuration->organization;
        $data['status'] = 'Aberta';
        $data['file'] = '/storage/loans/Cautela '.$data['graduation'].' - '.$data['name'].' '.$data['to'].' '.Carbon::now()->format('d.m.Y H\hi').'.pdf';


        Pdf::loadView('generate-loan-pdf', ['data' => $data, 'config' => Configuration::find(1)])->save(public_path().''.$data['file'].'')->stream('download.pdf');

        return $data;
    }


    protected function getSteps(): array
    {
        return [
            Step::make('Dados do Militar')
            ->description('Digite os dados de quem irá cautelar o material')
            ->schema([
                TextInput::make('to')
                    ->required()
                    ->label('Organização Militar')
                    ->live(),

                Select::make('graduation')
                    ->required()
                    ->label('Graduação')
                    ->live()
                    ->options([
                        'sd' => 'Soldado',
                        'cb' => 'Cabo',
                        '3ºsgt' => '3º SGT',
                        '2ºsgt' => '2º SGT',
                        '1ºsgt' => '1º SGT',
                        'sub' => 'Subtenente',
                        '2ºten' => '2º Tenente',
                        '1ºten' => '1º Tenente',
                        'cap' => 'Capitão',
                        'major' => 'Major',
                        'tencel' => 'Tenente Coronel',
                        'cel' => 'Coronel',
                    ]),

                    TextInput::make('name')
                    ->required()
                    ->label('Nome')
                    ->live(),

                    TextInput::make('idt')
                    ->required()
                    ->label('Identidade')
                    ->live(),

                    TextInput::make('contact')
                    ->mask('(99) 9-9999-9999')
                    ->required()
                    ->label('Contato')
                    ->length(16)
                    ->live()
            ])->columns(3),

            Step::make('Materiais')
                ->description('Selecione os Materiais Cautelados')
                ->schema([
                    Repeater::make('material_group')
                        ->schema([
                            Select::make('materials')
                                ->multiple()
                                ->label('Materiais')
                                ->getSearchResultsUsing(fn (string $search): array => Material::where('name', 'like', "%{$search}%")->where('status','Disponível')->limit(50)->pluck('name', 'id')->toArray())
                                ->getOptionLabelsUsing(fn (array $values): array => Material::whereIn('id', $values)->where('status', 'Disponível')->pluck('name', 'id')->toArray())
                                ->afterStateUpdated(function (?array $state, ?array $old) {
                                    $this->selectedMaterial = $state;
                                }),

                            TextInput::make('qtd')
                                ->label('Quantidade')
                                ->type('number')
                                ->numeric()
                                ->placeholder('Se deixado vazio será calculado automaticamente'),

                            TextInput::make('obs')
                                ->label('Observações')
                                ->placeholder('S/A')
                                ->default('S/A'),
                            ])
                            ->label('Materiais')
                            ->columns(3)
                            ->collapsible()
                            ->collapsible(),
        
                    DatePicker::make('return_date')
                    ->required()
                    ->label('Data de retorno')
                    ->live(),
                ]),

            
            ];
    }

    protected function getHeaderActions(): array
    {
        return [

        ];
    }
}
