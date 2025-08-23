<?php

namespace App\Filament\Resources\Conceptos\Pages;

use App\Filament\Resources\Conceptos\ConceptoResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;

class ManageConceptos extends ManageRecords
{
    protected static string $resource = ConceptoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
