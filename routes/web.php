<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect('app');
});

Route::get('ticket/pdf/{pago}', [App\Http\Controllers\TicketController::class, 'pdf'])->name('pdf');
