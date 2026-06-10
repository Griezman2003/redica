<?php

namespace App\Filament\Resources\Clientes\Pages;

use App\Filament\Resources\Clientes\ClienteResource;

use Filament\Resources\Pages\ViewRecord;

class ViewCliente extends ViewRecord
{
    protected static string $resource = ClienteResource::class;

    protected function getHeaderActions(): array
    {
        return [
            //Actions\DeleteAction::make(),
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return [
            \App\Filament\Resources\Clientes\Widgets\InfoOverview::class,
        ];
    }
}