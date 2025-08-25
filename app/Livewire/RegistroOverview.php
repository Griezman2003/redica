<?php

namespace App\Livewire;

use Filament\Widgets\ChartWidget;
use App\Models\Registro;
use Carbon\Carbon;

class RegistroOverview extends ChartWidget
{
    protected ?string $heading = 'Registros del sistema';

    protected array|string|int $columnSpan = 2;

    protected function getData(): array
    {
        $meses = collect(range(1, 12));
        $registrosPorMes = $meses->map(function ($m) {
            return Registro::whereMonth('created_at', $m)
                        ->whereYear('created_at', Carbon::now()->year)
                        ->count();
        });

        // Nombres de los meses en español
        $mesesNombre = ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'];

        return [
            'labels' => $mesesNombre,
            'datasets' => [
                [
                    'label' => 'Registros por mes',
                    'data' => array_values($registrosPorMes->toArray()),
                    'backgroundColor' => 'rgba(54, 162, 235, 0.2)',
                    'borderColor' => 'rgba(54, 162, 235, 1)',
                    'borderWidth' => 2,
                    'fill' => true,
                ],
            ],
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
