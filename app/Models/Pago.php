<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Barryvdh\DomPDF\Facade\Pdf;


class Pago extends Model
{
    protected $fillable = ['concepto_id', 'registro_id', 'nombre', 'monto', 'uuid', 'mes','pendiente'];

    protected $casts = [
    'mes' => 'array',
    ];

    public function registro()
    {
        return $this->belongsTo(Registro::class, 'registro_id');
    }
    
    public function concepto()
    {
        return $this->belongsTo(concepto::class);
    }

    
    /**
     * Metodo que retorna el contenido del pdf del ticket
     *
     * @return string
     */
    public function pdf(): string
    {
        $this->generarPdf($this->toArray());
        
        if (!\Illuminate\Support\Facades\Storage::disk('local')->exists("tickets/{$this->uuid}.pdf")) {
            $this->generarPdf($this->toArray());
        }
        return \Illuminate\Support\Facades\Storage::disk('local')->get("tickets/{$this->uuid}.pdf");
    }

    public function generarPdf(): string
    {
        $pdf = Pdf::loadView('pdf.ticket', [
            'data' => $this->toArray(),
            'pago' => $this->load('registro')
        ]);
        $pdf->setPaper([0, 0, 226.77, 500], 'portrait'); 
        $fileName = $this->uuid . '.pdf';

        \Illuminate\Support\Facades\Storage::disk('local')->put('tickets/' . $fileName, $pdf->output());

        return 'tickets/' . $fileName;
    }

    /**
     * Evento al crear un pago:
     * Si no existe un UUID asignado, se genera automáticamente.
     * @return void
     */
    protected static function booted()
    {
        static::creating(function ($pago) {
            if (!$pago->uuid) {
                $pago->uuid = (string) \Illuminate\Support\Str::uuid();
            }
        });
    }

    /**
     * Generar folio con un condigurador y el id del pago
     *
     * @return void
     */
    public function getFolioAttribute()
    {
        $idFormateado = str_pad($this->id ?? 0, 4, '0', STR_PAD_LEFT);
        return config('pago.folio') . $idFormateado;
    }

    /**
     * Generar una lista de meses a partir del array almacenado en la base de datos
     *
     * @return void
     */
    public function getMesesAttribute()
    {
        $meses = $this->mes;
        if (!$meses || !is_array($meses) || empty($meses)) {
            return '-';
        }
        $cantidad = count($meses);
        if (in_array($cantidad, [7,8,9, 10, 11, 12])) {
            return $cantidad === 12 ? '1 año' : "$cantidad meses";
        }
        return implode(', ', $meses);
    }



}
