<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Cliente extends Model
{
    protected $fillable = ['user_id', 'nombre', 'colonia', 'estado'];

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
        $meses = [
            'enero',
            'febrero',
            'marzo',
            'abril',
            'mayo',
            'junio',
            'julio',
            'agosto',
            'septiembre',
            'octubre',
            'noviembre',
            'diciembre',
        ];
        $pagados = $cliente->pago()
        ->pluck('mes')->flatten()->map(fn ($mes) => strtolower($mes))->toArray();
        
        foreach ($meses as $mes) {
            if (!in_array($mes, $pagados)) {
                return $mes;
            }
        }
        return null;
    }
}
