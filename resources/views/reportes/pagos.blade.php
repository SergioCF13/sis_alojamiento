<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reporte de Pagos</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; color: #1f2937; margin: 30px; }
        h1, h2, h3 { margin: 0 0 12px; }
        .header { border-bottom: 2px solid #0f172a; padding-bottom: 10px; margin-bottom: 20px; }
        table { width: 100%; border-collapse: collapse; margin-top: 12px; }
        th, td { border: 1px solid #d1d5db; padding: 8px; text-align: left; }
        th { background: #e2e8f0; }
        .small { font-size: 11px; }
    </style>
</head>
<body>
    <div class="header">
        <h1>Reporte de Pagos</h1>
        <div class="small">Periodo: {{ $fechaInicio }} al {{ $fechaFin }}</div>
    </div>

    <table>
        <tr>
            <th>#</th>
            <th>Cliente</th>
            <th>Monto</th>
            <th>Método</th>
            <th>Fecha</th>
        </tr>
        @forelse($pagosEnRango as $pago)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $pago->reserva?->cliente?->nombre_completo ?? 'Sin cliente' }}</td>
                <td>Bs {{ number_format($pago->monto, 2, ',', '.') }}</td>
                <td>{{ $pago->metodo_pago }}</td>
                <td>{{ $pago->fecha_pago }}</td>
            </tr>
        @empty
            <tr>
                <td colspan="5">No hay pagos registrados en este rango.</td>
            </tr>
        @endforelse
    </table>
</body>
</html>
