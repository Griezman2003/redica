<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Registro extends Model
{
    protected $fillable = ['concepto_id', 'nombre', 'monto'];
}
