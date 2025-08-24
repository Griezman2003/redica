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

    public static function generarPdf($data): string
    {
        $pdf = Pdf::loadView('pdf.ticket', [
            'data' => $data,
        ]);
        $fileName = $data['uuid']. '.pdf';
        \Illuminate\Support\Facades\Storage::disk('local')->put('tickets/' . $fileName, $pdf->output());
        return 'tickets/' . $fileName;
    }

    protected static function booted()
    {
        static::creating(function ($pago) {
            if (!$pago->uuid) {
                $pago->uuid = (string) \Illuminate\Support\Str::uuid();
            }
        });
    }

}
