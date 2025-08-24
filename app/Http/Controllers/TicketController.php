<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Registro;

class TicketController extends Controller
{
    public function pdf(Registro $registro)
    {
        return response($registro->pdf())
            ->header("Content-Type", "application/pdf")
            ->header(
                "Content-Disposition",
                'inline; filename="' . $registro->nombre . '.pdf"',
            );
    }
}
