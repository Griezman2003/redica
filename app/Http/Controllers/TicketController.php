<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pago;

class TicketController extends Controller
{
    public function pdf(Pago $pago)
    {
        return response($pago->pdf())
        ->header("Content-Type", "application/pdf")
        ->header(
            "Content-Disposition",
            'inline; filename="' . $pago->nombre . '.pdf"',
        );
    }
}
