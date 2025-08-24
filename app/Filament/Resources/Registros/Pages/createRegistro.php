<?php

namespace App\Filament\Resources\Registros\Pages;

use App\Filament\Resources\Registros\RegistroResource;
use Filament\Resources\Pages\CreateRecord;

class CreateRegistro extends CreateRecord
{
    protected static string $resource = RegistroResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['user_id'] = \Illuminate\Support\Facades\Auth::id();
        $data['uuid'] = \Illuminate\Support\Str::uuid()->toString();
        \App\Models\Registro::generarPdf($data);
        return $data;
    }

    // protected function afterCreate(): void
    // {
        
    // }
}
