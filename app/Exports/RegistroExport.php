<?php

namespace App\Exports;

use App\Models\Pago;
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
        return Pago::with('registro')->get();
    }
    /**
     * se especifica el nombre de los emcabezados del excel
     *
     * @return array
     */
    public function headings(): array
    {
        return [
            'nombre',
            'monto',
            'mes de pago',
            'uuid',
            'mes pendiente',
            'creado el',
            'actualizado el'
        ];
    }

    /**
     * se encargar de poner los encabezados
     *
     * @param [type] $registro
     * @return array
     */
    public function map($pago): array
    {
        return [
            $pago->registro->nombre,
            '$' . number_format($pago->monto, 2),
            $pago->mes,
            $pago->uuid,
            $pago->pendiente,
            $pago->created_at->format('d/m/Y H:i'),
            $pago->updated_at->format('d/m/Y H:i'),
        ];
    }
}
