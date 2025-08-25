<?php

namespace App\Livewire;

use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use App\Models\Pago;
use Carbon\Carbon;

class PagoOverview extends StatsOverviewWidget
{
    protected ?string $heading = 'Pagos del sistema';

    protected array|string|int $columnSpan = 2;

    protected function getStats(): array
    {
        
        $pagosHoy = Pago::whereDate('created_at', Carbon::today())->count();

        $montoPagosMes = Pago::whereYear('created_at', Carbon::now()->year)
                    ->whereMonth('created_at', Carbon::now()->month)
                    ->sum('monto');

        $totalPagos = Pago::whereYear('created_at', Carbon::now()->year)->sum('monto');

        $horasHoy = collect(range(0, 23));
        $pagosPorHora = $horasHoy->map(function ($h) {
            return Pago::whereDate('created_at', Carbon::today())
                    ->whereRaw('HOUR(created_at) = ?', [$h])
                    ->count();
        });


        $diasDelMes = collect(range(1, Carbon::now()->daysInMonth));
        $montoPorDia = $diasDelMes->map(function ($d) {
            return Pago::whereDay('created_at', $d)
                    ->whereMonth('created_at', Carbon::now()->month)
                    ->whereYear('created_at', Carbon::now()->year)
                    ->sum('monto');
        });

        $meses = collect(range(1, 12));
        $montoPorMes = $meses->map(function ($m) {
            return Pago::whereMonth('created_at', $m)
                    ->whereYear('created_at', Carbon::now()->year)
                    ->sum('monto');
        });

        return [
            Stat::make('Pagos Hoy', $pagosHoy)
                ->description($pagosHoy . ' pagos')
                ->color('success')
                ->icon('heroicon-o-calendar')
                ->chart(array_values($pagosPorHora->toArray())),

            Stat::make('Monto Mensual', $montoPagosMes)
                ->description('$' . number_format($montoPagosMes, 2))
                ->color('primary')
                ->icon('heroicon-o-calendar-days')
                ->chart(array_values($montoPorDia->toArray())),

            Stat::make('Monto Anual', $totalPagos)
                ->description('$' . number_format($totalPagos, 2))
                ->color('success')
                ->icon('heroicon-o-currency-dollar')
                ->chart(array_values($montoPorMes->toArray())),
        ];
    }
}
