<?php

namespace App\Exports;

use App\Models\Pago;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class RegistroExport implements FromCollection, WithHeadings, WithMapping
{
    protected $ids;

    /**
     * Recibe los IDs de pagos seleccionados
     */
    public function __construct(array $ids)
    {
        $this->ids = $ids;
    }

    /**
     * Retorna solo los pagos seleccionados con sus relaciones
     */
    public function collection()
    {
        return Pago::with(['cliente', 'concepto'])
            ->whereIn('id', $this->ids)
            ->get();
    }

    /**
     * Encabezados del Excel
     */
    public function headings(): array
    {
        return [
            'Nombre',
            'Monto',
            'Mes de pago',
            'Colonia',
            'Folio del Ticket',
            'Concepto',
            'Creado el',
            'Actualizado el'
        ];
    }

    /**
     * Mapear cada fila del Excel
     */
    public function map($pago): array
    {
        return [
            $pago->cliente->nombre ?? 'N/A',
            '$' . number_format($pago->monto, 2),
            is_array($pago->mes) ? implode(', ', $pago->mes) : $pago->mes,
            $pago->cliente->colonia ?? 'Sin colonia',
            $pago->folio,
            $pago->concepto->nombre ?? 'N/A',
            $pago->created_at->format('d/m/Y H:i'),
            $pago->updated_at->format('d/m/Y H:i'),
        ];
    }
}
