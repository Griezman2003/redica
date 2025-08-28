<?php

namespace App\Livewire;

use Filament\Widgets\ChartWidget;
use App\Models\Registro;
use Carbon\Carbon;

class RegistroOverview extends ChartWidget
{
    protected ?string $heading = 'Registros por mes';
    
    protected static ?int $sort = 1;
    
    protected array|string|int $columnSpan = 2;

    protected function getType(): string
    {
        return 'line';
    }

    protected function getData(): array
    {
        $meses = collect(range(1, 12));
        $registrosPorMes = $meses->map(function ($m) {
            return Registro::whereMonth('created_at', $m)
                        ->whereYear('created_at', Carbon::now()->year)
                        ->count();
        });

        return [
            'datasets' => [
                [
                    'label' => 'Registros',
                    'data' => $registrosPorMes->values()->toArray(),
                    'fill' => 'start',
                ],
            ],
            'labels' => ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
        ];
    }
}
