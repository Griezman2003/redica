<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Concepto extends Model
{
    protected $fillable = ['nombre', 'descripcion', 'atributos'];
}
