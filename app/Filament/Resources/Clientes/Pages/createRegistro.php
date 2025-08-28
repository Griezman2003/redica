<?php

namespace App\Filament\Resources\Clientes\Pages;

use App\Filament\Resources\Clientes\ClienteResource;
use Filament\Resources\Pages\CreateRecord;

class createRegistro extends CreateRecord
{
    protected static string $resource = ClienteResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['user_id'] = \Illuminate\Support\Facades\Auth::id();
        return $data;
    }

    // protected function afterCreate(): void
    // {
        
    // }
}
