<div style="font-family: sans-serif; font-size: 14px;">
    <h2>Ticket</h2>
    <p><strong>Cliente:</strong> {{ $data['nombre'] ?? 'N/A' }}</p>
    <p><strong>Monto:</strong> {{ $data['monto'] ?? '0.00' }}</p>
    <p><strong>Fecha:</strong> {{ now()->format('d/m/Y H:i') }}</p>
</div>
