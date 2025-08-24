<?php

namespace App\Filament\Resources\Registros\Pages;

use App\Filament\Resources\Registros\RegistroResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditRegistro extends EditRecord
{
    protected static string $resource = RegistroResource::class;

    protected function getHeaderActions(): array
    {
        return [
            //Actions\DeleteAction::make(),
        ];
    }
}