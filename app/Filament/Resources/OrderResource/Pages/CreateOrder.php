<?php

namespace App\Filament\Resources\OrderResource\Pages;

use Filament\Actions;
use App\Models\Inventory;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Filament\Resources\OrderResource;
use Filament\Resources\Pages\CreateRecord;

class CreateOrder extends CreateRecord
{
    protected static string $resource = OrderResource::class;

    protected function getRedirectUrl(): string
    {   // permite regresar a la parte del index al crear un  formulario video 6 minuto 10
        return $this->getResource()::getUrl('index');
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data["user_id"] = Auth::user()->id;
        return $data;
    }

    protected function afterCreate(): void
    {
        DB::transaction(function () {
            $this->record->load('orderProducts');

            //dd($items);

            foreach ( $this->record->orderProducts as $pivot) {
                Inventory::query()
                    ->where('warehouse_id', $this->record->warehouse_id)
                    ->where('product_id', $pivot->product_id)
                    ->decrement('stock', $pivot->quantity);

            }
        });
    }
}
