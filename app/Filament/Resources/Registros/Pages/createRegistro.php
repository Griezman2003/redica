<?php

namespace App\Filament\Resources\Registros\Pages;

use App\Filament\Resources\Registros\RegistroResource;
use Filament\Resources\Pages\CreateRecord;

class createRegistro extends CreateRecord
{
    protected static string $resource = RegistroResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['user_id'] = \Illuminate\Support\Facades\Auth::id();
        return $data;
    }

    // protected function afterCreate(): void
    // {
        
    // }
}
