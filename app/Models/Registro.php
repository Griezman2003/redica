<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Barryvdh\DomPDF\Facade\Pdf;

class Registro extends Model
{
    protected $fillable = ['user_id', 'nombre', 'estado'];

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
}
