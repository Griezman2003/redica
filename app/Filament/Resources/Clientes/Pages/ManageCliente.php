<?php

namespace App\Filament\Resources\Clientes\Pages;

use App\Filament\Resources\Clientes\ClienteResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageCliente extends ManageRecords
{
    protected static string $resource = ClienteResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()->createAnother(false)
            ->using(function (array $data) {
                $data['user_id'] = \Illuminate\Support\Facades\Auth::id();
                return static::getResource()::getModel()::create($data);
            }),  
        ];
    }
}
