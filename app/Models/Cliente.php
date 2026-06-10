<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Cliente extends Model
{
    protected $fillable = ['user_id', 'nombre', 'colonia', 'estado', 'telefono', 'calle'];

    public function concepto()
    {
        return $this->belongsTo(Concepto::class);
    }

    public function pago()
    {
        return $this->hasMany(Pago::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

public static function obtenerMesPendiente($cliente)
{
    if (! $cliente) {
        return null;
    }

    $meses = [
        'enero', 'febrero', 'marzo', 'abril', 'mayo', 'junio',
        'julio', 'agosto', 'septiembre', 'octubre', 'noviembre', 'diciembre'
    ];

    $anioActual = (int) date('Y');

    $pagosDelAnio = $cliente->pago()
        ->whereYear('created_at', $anioActual)
        ->pluck('mes')
        ->map(fn ($mes) => strtolower(trim($mes)))
        ->toArray(); 

    foreach ($meses as $mes) {
        $mesPagado = false;

        foreach ($pagosDelAnio as $pagoGuardado) {
            if (str_contains($pagoGuardado, $mes)) {
                $mesPagado = true;
                break; 
            }
        }

        if (! $mesPagado) {
            return strtoupper($mes);
        }
    }
    return 'ENERO';
}

}
