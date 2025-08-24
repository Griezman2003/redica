<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Barryvdh\DomPDF\Facade\Pdf;

class Registro extends Model
{
    protected $fillable = ['concepto_id', 'nombre', 'monto', 'uuid', 'estado'];

    public function concepto()
    {
        return $this->belongsTo(Concepto::class);
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
}
