<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reporte de Habitaciones</title>
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
        <h1>Reporte de Habitaciones</h1>
        <div class="small">Periodo: {{ $fechaInicio }} al {{ $fechaFin }}</div>
    </div>

    <table>
        <tr>
            <th>#</th>
            <th>Número</th>
            <th>Piso</th>
            <th>Estado</th>
            <th>Tipo</th>
        </tr>
        @forelse($habitacionesEnRango as $habitacion)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $habitacion->numero }}</td>
                <td>{{ $habitacion->piso }}</td>
                <td>{{ $habitacion->estado }}</td>
                <td>{{ $habitacion->tipoHabitacion?->nombre ?? 'N/A' }}</td>
            </tr>
        @empty
            <tr>
                <td colspan="5">No hay habitaciones registradas en este rango.</td>
            </tr>
        @endforelse
    </table>
</body>
</html>
