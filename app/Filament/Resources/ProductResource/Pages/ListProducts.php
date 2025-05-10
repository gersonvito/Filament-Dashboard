<?php

namespace App\Filament\Resources\ProductResource\Pages;

use App\Filament\Resources\ProductResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListProducts extends ListRecords
{
    protected static string $resource = ProductResource::class;

    protected static ?string $title = 'Lista de productos';

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
            ->label('Crear producto')
                ->icon('heroicon-o-plus')
                ->color('primary')
                ->modalHeading('Crear nuevo producto')
                ->form([
                    // Define your form fields here
                ]),
        ];
    }
}
