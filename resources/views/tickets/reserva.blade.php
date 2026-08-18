<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ticket de Reserva</title>
    <style>
        body {
            font-family: 'Helvetica Neue', Arial, sans-serif;
            color: #222;
            background: #f2f2f2;
            margin: 0;
            padding: 20px;
        }
        .ticket-sheet {
            width: 380px;
            margin: 0 auto 20px;
            background: #fff;
            border: 1px solid #dcdcdc;
            border-radius: 10px;
            box-shadow: 0 2px 12px rgba(0, 0, 0, 0.08);
            overflow: hidden;
        }
        .ticket-body {
            padding: 20px;
        }
        .ticket-header {
            background: linear-gradient(135deg, #1f4d78 0%, #4f7bb3 100%);
            color: #fff;
            padding: 18px 20px;
            text-align: center;
        }
        .ticket-header .brand {
            font-size: 16px;
            letter-spacing: 2px;
            margin-bottom: 4px;
            font-weight: 700;
        }
        .ticket-header .type {
            font-size: 12px;
            opacity: 0.92;
            margin-top: 4px;
        }
        .copy-label {
            text-align: center;
            font-size: 12px;
            color: #555;
            margin: 16px 0 10px;
            font-weight: 700;
        }
        .line-divider {
            height: 1px;
            background: #e1e1e1;
            margin: 16px 0;
        }
        .row {
            display: flex;
            justify-content: space-between;
            margin: 8px 0;
            font-size: 13px;
        }
        .row strong {
            color: #333;
        }
        .section-title {
            font-size: 12px;
            margin: 18px 0 8px;
            color: #444;
            font-weight: 700;
            letter-spacing: 0.5px;
        }
        .footer {
            margin-top: 18px;
            font-size: 11px;
            color: #666;
            text-align: center;
            line-height: 1.4;
        }
        .section-columns {
            display: flex;
            justify-content: space-between;
            gap: 10px;
            flex-wrap: wrap;
        }
        .section-columns .col {
            width: 48%;
        }
        .meta-row {
            margin: 6px 0;
        }
        @media print {
            body {
                background: #fff;
                padding: 0;
            }
            .ticket-sheet {
                box-shadow: none;
                border: none;
                margin-bottom: 24px;
                page-break-after: always;
                break-after: page;
            }
            .ticket-sheet:last-child {
                margin-bottom: 0;
                page-break-after: auto;
                break-after: auto;
            }
            .cut-marker {
                display: none;
            }
        }
    </style>
</head>
<body onload="startPrint();">
    @foreach(['Copia Cliente', 'Copia Administración'] as $copyLabel)
        <div class="ticket-sheet">
            <div class="ticket-header">
                <div class="brand">HOTEL</div>
                <div class="type">{{ $ticketTitle }}</div>
            </div>
            <div class="ticket-body">
                <div class="copy-label">{{ $copyLabel }}</div>
                <div class="line-divider"></div>

                <div class="section-title">Datos de Reserva</div>
                <div class="row"><span><strong>Reserva #</strong></span><span>{{ $reserva->id }}</span></div>
                <div class="row"><span><strong>Cliente</strong></span><span>{{ $reserva->cliente->nombre_completo ?? 'N/A' }}</span></div>
                <div class="row"><span><strong>Habitación</strong></span><span>{{ $reserva->habitacion->numero ?? 'Sin asignar' }}</span></div>
                <div class="row"><span><strong>Estado</strong></span><span>{{ $reserva->estado }}</span></div>
                <div class="row"><span><strong>Ingreso</strong></span><span>{{ $reserva->fecha_ingreso }} {{ $reserva->hora_ingreso }}</span></div>
                <div class="row"><span><strong>Salida</strong></span><span>{{ $reserva->fecha_salida }} {{ $reserva->hora_salida }}</span></div>
                <div class="row"><span><strong>Personas</strong></span><span>{{ $reserva->cantidad_persona }}</span></div>

                <div class="section-title">Resumen de Pago</div>
                <div class="row"><span><strong>Precio total</strong></span><span>Bs {{ number_format($precio, 2) }}</span></div>
                <div class="row"><span><strong>Monto pagado</strong></span><span>Bs {{ number_format($montoPagado, 2) }}</span></div>
                <div class="row"><span><strong>Saldo pendiente</strong></span><span>Bs {{ number_format($saldoPendiente, 2) }}</span></div>
                <div class="row"><span><strong>Estado de pago</strong></span><span>{{ $saldoPendiente > 0 ? 'Pago pendiente' : 'Pagado' }}</span></div>

                <div class="section-title">Observaciones</div>
                <div class="meta-row">{{ $reserva->observaciones ?? 'Sin observaciones' }}</div>
                <div class="footer">Gracias por su preferencia. Documento válido para registro interno.</div>
            </div>
        </div>
        @if(!$loop->last)
            <div style="width: 380px; margin: 0 auto; text-align:center; color:#999; font-size:12px; letter-spacing:1px;">--- Cortar aquí ---</div>
        @endif
    @endforeach

    <script>
        function startPrint() {
            window.print();
            setTimeout(() => {
                window.location.href = document.referrer || '{{ route('reservas.index') }}';
            }, 500);
        }
    </script>
</body>
</html>
