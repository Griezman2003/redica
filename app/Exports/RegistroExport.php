<?php

namespace App\Exports;

use App\Models\Registro;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class RegistroExport implements FromCollection , WithHeadings, WithMapping
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return Registro::select('nombre', 'monto', 'estado', 'mes', 'uuid', 'created_at', 'updated_at')->get();
    }

    
    public function headings(): array
    {
        return [
            'nombre',
            'monto',
            'estado',
            'mes',
            'uuid',
            'created_at',
            'updated_at'
        ];
    }

    /**
     * se encargar de poner los encabezados
     *
     * @param [type] $registro
     * @return array
     */
    public function map($registro): array
    {
        return [
            $registro->nombre,
            $registro->monto,
            $registro->estado ? 'Activo' : 'No activo',
            $registro->mes,
            $registro->uuid,
            $registro->created_at->format('d/m/Y H:i'),
            $registro->updated_at->format('d/m/Y H:i'),
        ];
    }
}
