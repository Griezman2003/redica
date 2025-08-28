<?php

namespace App\Filament\Resources\Registros\Pages;

use App\Filament\Resources\Registros\RegistroResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageRegistros extends ManageRecords
{
    protected static string $resource = RegistroResource::class;

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
