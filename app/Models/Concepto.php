<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Concepto extends Model
{
    protected $fillable = ['nombre', 'descripcion', 'atributos'];

    public function registros()
    {
        return $this->hasMany(Registro::class);
    }

    protected $casts = [
    'atributos' => 'array',
    ];
}
