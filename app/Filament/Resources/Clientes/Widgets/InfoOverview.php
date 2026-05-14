<?php

namespace App\Filament\Resources\Clientes\Widgets;

use Filament\Widgets\StatsOverviewWidget\Stat;
use Filament\Widgets\StatsOverviewWidget as Widget;
use App\Models\Cliente;

class InfoOverview extends Widget
{
    public ?Cliente $record = null;

    protected function getStats(): array
    {
        $cliente = $this->record;

        if (!$cliente) {
            return [];
        }

        $totalPagado = $cliente->pago->sum('monto');
        $mesesPendientes = \App\Models\Cliente::obtenerMesPendiente($cliente);

        return [
            Stat::make('Estado', $cliente->estado ? 'Activo' : 'Inactivo')
                ->color($cliente->estado ? 'primary' : 'danger')
                ->icon($cliente->estado ? 'heroicon-o-check-circle' : 'heroicon-o-x-circle'),

            Stat::make('Total pagado', '$' . number_format($totalPagado, 2))
                ->color('primary')
                ->icon('heroicon-o-banknotes'),

            Stat::make('Mes pendiente', $mesesPendientes)
                ->color($mesesPendientes > 0 ? 'warning' : 'success')
                ->icon('heroicon-o-calendar'),

            Stat::make('Pagos realizados', $cliente->pago->count())
                ->color('danger')
                ->icon('heroicon-o-receipt-refund'),
        ];
    }
}
