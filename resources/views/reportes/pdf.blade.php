<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reporte de Alojamiento</title>
    <style>
        @page {
            margin: 30px 35px;
        }
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            color: #1e293b;
            font-size: 11px;
            line-height: 1.4;
        }
        
        /* HEADER */
        .header-table {
            width: 100%;
            border-bottom: 2px solid #0f172a;
            padding-bottom: 12px;
            margin-bottom: 20px;
        }
        .header-title {
            font-size: 20px;
            font-weight: bold;
            color: #0f172a;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .header-subtitle {
            font-size: 11px;
            color: #64748b;
            margin-top: 3px;
        }
        .meta-box {
            text-align: right;
            font-size: 10px;
            color: #475569;
        }

        /* METRIC CARDS (Compatible con Dompdf) */
        .summary-table {
            width: 100%;
            margin-bottom: 20px;
            border-spacing: 10px 0;
            margin-left: -10px;
            margin-right: -10px;
        }
        .card-cell {
            width: 33.33%;
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 6px;
            padding: 12px;
            vertical-align: top;
        }
        .card-label {
            font-size: 9px;
            text-transform: uppercase;
            font-weight: bold;
            color: #64748b;
            margin-bottom: 4px;
        }
        .card-value {
            font-size: 16px;
            font-weight: bold;
            color: #0f172a;
        }
        .card-subtext {
            font-size: 10px;
            color: #64748b;
            margin-top: 2px;
        }

        /* SECCIONES Y TABLAS */
        .section-title {
            font-size: 13px;
            font-weight: bold;
            color: #0f172a;
            margin-top: 18px;
            margin-bottom: 8px;
            border-left: 3px solid #2563eb;
            padding-left: 8px;
            text-transform: uppercase;
        }

        table.data-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
            page-break-inside: avoid;
        }
        table.data-table th, 
        table.data-table td {
            border: 1px solid #cbd5e1;
            padding: 7px 9px;
            text-align: left;
            vertical-align: middle;
        }
        table.data-table th {
            background-color: #f1f5f9;
            color: #334155;
            font-size: 10px;
            font-weight: bold;
            text-transform: uppercase;
        }
        table.data-table tr:nth-child(even) td {
            background-color: #f8fafc;
        }

        /* UTILIDADES */
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .text-bold { font-weight: bold; }
        .text-success { color: #16a34a; }
        .text-muted { color: #64748b; }
        .badge {
            display: inline-block;
            padding: 2px 6px;
            font-size: 9px;
            font-weight: bold;
            border-radius: 4px;
            background-color: #e2e8f0;
            color: #334155;
        }
        .badge-success { background-color: #dcfce7; color: #166534; }

        /* FOOTER */
        .footer {
            position: fixed;
            bottom: -15px;
            left: 0;
            right: 0;
            text-align: center;
            font-size: 9px;
            color: #94a3b8;
            border-top: 1px solid #e2e8f0;
            padding-top: 6px;
        }
    </style>
</head>
<body>

    {{-- PIE DE PÁGINA FIX --}}
    <div class="footer">
        Reporte generado automáticamente por el Sistema de Gestión Hotelera &mdash; Página <span class="page-number"></span>
    </div>

    {{-- ENCABEZADO --}}
    <table class="header-table">
        <tr>
            <td style="vertical-align: middle;">
                <div class="header-title">Reporte de Alojamiento</div>
                <div class="header-subtitle">Resumen consolidado de operaciones e ingresos</div>
            </td>
            <td class="meta-box" style="vertical-align: middle;">
                <div><strong>Periodo:</strong> {{ \Carbon\Carbon::parse($fechaInicio)->format('d/m/Y') }} al {{ \Carbon\Carbon::parse($fechaFin)->format('d/m/Y') }}</div>
                <div><strong>Emisión:</strong> {{ \Carbon\Carbon::now()->format('d/m/Y H:i') }}</div>
            </td>
        </tr>
    </table>

    {{-- RESUMEN KPI CARDS --}}
    <table class="summary-table">
        <tr>
            <td class="card-cell">
                <div class="card-label">Clientes Registrados</div>
                <div class="card-value">{{ $clientesEnRangoCount ?? $clientesEnRango->count() }}</div>
                <div class="card-subtext">En el periodo seleccionado</div>
            </td>
            <td class="card-cell">
                <div class="card-label">Habitaciones</div>
                <div class="card-value">{{ $habitacionesEnRangoCount ?? $habitacionesEnRango->count() }}</div>
                <div class="card-subtext">Con actividad/registro</div>
            </td>
            <td class="card-cell">
                <div class="card-label">Total Ingresos</div>
                <div class="card-value text-success">Bs {{ number_format($montoPagos, 2, ',', '.') }}</div>
                <div class="card-subtext">Recaudación acumulada</div>
            </td>
        </tr>
    </table>

    {{-- RESUMEN GENERAL --}}
    <div class="section-title">Resumen Operativo</div>
    <table class="data-table">
        <thead>
            <tr>
                <th>Concepto</th>
                <th class="text-right" style="width: 30%;">Cantidad / Valor</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>Reservas realizadas</td>
                <td class="text-right text-bold">{{ $totalReservas }}</td>
            </tr>
            <tr>
                <td>Transacciones de pago registradas</td>
                <td class="text-right text-bold">{{ $totalPagos }}</td>
            </tr>
            <tr>
                <td>Clientes activos en el periodo</td>
                <td class="text-right text-bold">{{ $clientesEnRangoCount ?? $clientesEnRango->count() }}</td>
            </tr>
            <tr>
                <td>Habitaciones ocupadas / reservadas</td>
                <td class="text-right text-bold">{{ $habitacionesEnRangoCount ?? $habitacionesEnRango->count() }}</td>
            </tr>
        </tbody>
    </table>

    {{-- DETALLE DE RESERVAS --}}
    <div class="section-title">Detalle de Reservas</div>
    <table class="data-table">
        <thead>
            <tr>
                <th>Cliente</th>
                <th class="text-center">Habitación</th>
                <th class="text-center">Fecha Ingreso</th>
                <th class="text-center">Fecha Salida</th>
                <th class="text-center">Estado</th>
            </tr>
        </thead>
        <tbody>
            @forelse($reservasEnRango as $reserva)
                <tr>
                    <td class="text-bold">{{ $reserva->cliente?->nombre_completo ?? 'Sin cliente' }}</td>
                    <td class="text-center">Hab. {{ $reserva->habitacion?->numero ?? 'N/A' }}</td>
                    <td class="text-center">{{ \Carbon\Carbon::parse($reserva->fecha_ingreso)->format('d/m/Y') }}</td>
                    <td class="text-center">{{ \Carbon\Carbon::parse($reserva->fecha_salida)->format('d/m/Y') }}</td>
                    <td class="text-center">
                        <span class="badge">{{ $reserva->estado }}</span>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="text-center text-muted py-3">No hay reservas registradas en este rango de fechas.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    {{-- DETALLE DE PAGOS --}}
    <div class="section-title">Detalle de Pagos</div>
    <table class="data-table">
        <thead>
            <tr>
                <th>Cliente</th>
                <th class="text-right">Monto</th>
                <th class="text-center">Método de Pago</th>
                <th class="text-center">Fecha de Pago</th>
            </tr>
        </thead>
        <tbody>
            @forelse($pagosEnRango as $pago)
                <tr>
                    <td>{{ $pago->reserva?->cliente?->nombre_completo ?? 'Sin cliente' }}</td>
                    <td class="text-right text-bold text-success">Bs {{ number_format($pago->monto, 2, ',', '.') }}</td>
                    <td class="text-center"><span class="badge">{{ $pago->metodo_pago }}</span></td>
                    <td class="text-center">{{ \Carbon\Carbon::parse($pago->fecha_pago)->format('d/m/Y') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" class="text-center text-muted py-3">No hay transacciones de pago en este rango de fechas.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

</body>
</html>