<?php

namespace App\Filament\Resources\Registros\Pages;

use App\Filament\Resources\Registros\RegistroResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;

class ManageRegistros extends ManageRecords
{
    protected static string $resource = RegistroResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
