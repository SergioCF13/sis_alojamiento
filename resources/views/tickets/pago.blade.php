<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ticket de Pago</title>
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
                <div class="type">Ticket de Pago</div>
            </div>
            <div class="ticket-body">
                <div class="copy-label">{{ $copyLabel }}</div>
                <div class="line-divider"></div>

                <div class="section-title">Información de Pago</div>
                <div class="row"><span><strong>Pago #</strong></span><span>{{ $pago->id }}</span></div>
                <div class="row"><span><strong>Reserva #</strong></span><span>{{ $pago->reserva->id ?? 'N/A' }}</span></div>
                <div class="row"><span><strong>Cliente</strong></span><span>{{ $pago->reserva->cliente->nombre_completo ?? 'N/A' }}</span></div>
                <div class="row"><span><strong>Fecha</strong></span><span>{{ $pago->fecha_pago }}</span></div>
                <div class="row"><span><strong>Método</strong></span><span>{{ $pago->metodo_pago }}</span></div>
                <div class="row"><span><strong>Estado</strong></span><span>{{ $pago->estado }}</span></div>
                <div class="row"><span><strong>Monto</strong></span><span>Bs {{ number_format($pago->monto, 2) }}</span></div>

                <div class="section-title">Observaciones</div>
                <div class="meta-row">{{ $pago->observaciones ?? 'Sin observaciones' }}</div>
                <div class="footer">Gracias por su pago. Conserve esta copia como comprobante.</div>
            </div>
        </div>
        @if(!$loop->last)
            <div style="width: 380px; margin: 0 auto; text-align:center; color:#999; font-size:12px; letter-spacing:1px;">--- Cortar aquí ---</div>
        @endif
    @endforeach

    <script>
        const pagosIndexUrl = '{{ route('pagos.index') }}';

        function redirectAfterPrint() {
            window.location.href = pagosIndexUrl;
        }

        function startPrint() {
            window.print();
            setTimeout(redirectAfterPrint, 800);
        }

        window.addEventListener('afterprint', redirectAfterPrint);
    </script>
</body>
</html>
