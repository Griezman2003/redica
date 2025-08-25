<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Barryvdh\DomPDF\Facade\Pdf;


class Pago extends Model
{
    protected $fillable = ['concepto_id', 'registro_id', 'nombre', 'monto', 'uuid', 'mes','pendiente'];

    public function registro()
    {
        return $this->belongsTo(Registro::class);
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
        if (!\Illuminate\Support\Facades\Storage::disk('local')->exists("tickets/{$this->uuid}.pdf")) {
            $this->generarPdf($this->toArray());
        }
        return \Illuminate\Support\Facades\Storage::disk('local')->get("tickets/{$this->uuid}.pdf");
    }

    public function generarPdf(): string
    {
        $pdf = Pdf::loadView('pdf.ticket', [
            'data' => $this->toArray(),
        ]);
        $pdf->setPaper([0, 0, 226.77, 600], 'portrait'); 
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

}
