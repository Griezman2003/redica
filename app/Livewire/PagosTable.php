<?php

namespace App\Livewire;

use App\Models\Pago;
use App\Filament\Resources\Registros\RelationManagers\PagoRelationManager;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class PagosTable extends BaseWidget
{
    protected array|string|int $columnSpan = 2;

    protected static ?int $sort = 2;
    
    protected static ?string $heading = 'Pagos generales de los clientes';

    public function table(Table $table): Table
    {                
        $tabla = new PagoRelationManager()->table($table);

        return $tabla->query(Pago::query())
        ->headerActions([])
        ->recordActions([]);
    }
}