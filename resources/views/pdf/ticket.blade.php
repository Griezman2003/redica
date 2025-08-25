@php
    use Carbon\Carbon;
    $fecha = Carbon::now()->locale('es')->isoFormat('D [de] MMMM [del] YYYY');
@endphp
<div style="display: flex; justify-content: space-between; align-items: center; font-family: sans-serif; font-size: 12px; margin-bottom: 1px;">
    <h5>Candelaria, Campeche a {{ $fecha }}</h5>
</div>
<h5 style="text-align: right;"><strong>Folio:</strong> {{$pago->folio}}</h5>
<div style="font-family: sans-serif; font-size: 12px; text-align: center; width: 100%;">
    <div style="margin-bottom: 5px;">
        <img src="{{ public_path('img/logo.jpg') }}"
            alt="Logo Empresa"
            style="height: 80px; width: auto;">
    </div>

    <h2 style="margin: 5px 0; font-size: 14px; margin-botoom:10px">Ticket de Pago</h2>

    <div style="text-align: left; margin: 0 5px;">
        <p style="margin: 2px 0;"><strong>Codigo Postal:</strong> 24330</p>
        <p style="margin: 2px 0;"><strong>Direccion:</strong> Calle 1 de julio entre 4 </p>
        <p style="margin: 2px 0;"><strong>Fecha:</strong> {{ now()->format('d/m/Y') }}</p>
        <p style="margin: 2px 0;"><strong>Empresa:</strong> Red Ica</p>
    </div>

    <hr style="border: 0; border-top: 1px dashed #000; margin: 5px 0;">

    <div style="text-align: left; margin: 0 5px;">
        <p style="margin: 2px 0;"><strong>Cliente:</strong> {{ $pago->registro->nombre ?? 'N/A' }}</p>
        <p style="margin: 2px 0;"><strong>Mes de pago:</strong> {{ is_array($data['mes']) ? implode(', ', $data['mes']) : 'N/A' }}</p>
        <p style="margin: 2px 0;"><strong>Monto:</strong> ${{ number_format($data['monto'] ?? 0, 2) }}</p>
        <p style="margin: 2px 0;"><strong>Concepto de pago:</strong> {{ $pago->concepto->nombre}}</p>
        {{-- <p style="margin: 2px 0;">
            <strong>Pendiente:</strong> 
            @if(is_array($data['pendiente']) && count($data['pendiente']) > 0)
                {{ implode(', ', $data['pendiente']) }}
            @else
                No tiene adeudo
            @endif
        </p> --}}

    </div>

    <hr style="border: 0; border-top: 1px dashed #000; margin: 5px 0;">

    <p style="margin: 2px 0; font-size: 11px; text-align: center;">
        Hola {{ $pago->registro->nombre}} gracias por su pago y por su preferencia en nuestro servicio.
    </p>

    <hr style="border: 0; border-top: 1px dashed #000; margin: 5px 0;">

    <p style="margin-top: 10px; font-size: 11px; text-align: center;">
        ¡Muchas Gracias!
    </p>
</div>
