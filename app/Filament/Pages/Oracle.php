<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use Filament\Actions\Action;

class Oracle extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-chat-bubble-left-right';

    protected static string $view = 'filament.pages.oracle';
    
    protected static ?string $navigationLabel = 'Antigão (Oráculo)';

    protected static ?string $modelLabel = 'Oráculo';

    protected static ?string $title = 'O Antigão';

    protected ?string $subheading = 'Aqui é possível fazer perguntas ao Antigão (Oráculo)';

    protected static ?int $navigationSort = 7;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('Atualizar o Antigão')
                ->url('update-oracles/create'),
        ];
    }

    protected function getFooterWidgets(): array
    {
        return [
            \App\Filament\Resources\UpdateOracleResource\Widgets\OracleLearnCurve::class,
        ];
    }
}

