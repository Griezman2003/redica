<?php

namespace App\Livewire;

use Filament\Widgets\ChartWidget;
use App\Models\Cliente;
use Carbon\Carbon;

class ClienteOverview extends ChartWidget
{
    protected ?string $heading = 'Registros de clientes por mes';
    
    protected static ?int $sort = 3;
    
    protected array|string|int $columnSpan = 2;

    protected function getType(): string
    {
        return 'line';
    }

    /**
     * Metodo que filtra los años del cliente
     *
     * @return array
     */
    protected function getFilters(): array
    {
        $anios = Cliente::selectRaw('YEAR(created_at) as year')
            ->distinct()
            ->orderBy('year', 'desc')
            ->pluck('year')
            ->mapWithKeys(fn ($year) => [(string) $year => (string) $year])
            ->toArray();

        return $anios;
    }

    protected function getData(): array
    {
        $anio = $this->filter ?? Carbon::now()->year;

        $meses = collect(range(1, 12));

        $registrosPorMes = $meses->map(function ($m) use ($anio) {
            return Cliente::whereMonth('created_at', $m)
                        ->whereYear('created_at', $anio)
                        ->count();
        });

        return [
            'datasets' => [
                [
                    'label' => "Registros $anio",
                    'data' => $registrosPorMes->values()->toArray(),
                    'fill' => 'start',
                ],
            ],
            'labels' => [
                'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio',
                'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'
            ],
        ];
    }
}
