@extends('adminlte::page')

@section('title', 'Dashboard - Reportes')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center my-2">
        <div>
            <h1 class="h3 font-weight-bold text-dark mb-1">Dashboard General</h1>
            <p class="text-muted small mb-0">Monitoreo de ingresos, ocupación de habitaciones y reservas</p>
        </div>
        <div>
            <span class="badge badge-light border shadow-sm px-3 py-2 text-secondary font-weight-normal">
                <i class="fas fa-calendar-alt text-primary mr-2"></i>
                {{ \Carbon\Carbon::parse($fechaInicio)->format('d/m/Y') }} &mdash; {{ \Carbon\Carbon::parse($fechaFin)->format('d/m/Y') }}
            </span>
        </div>
    </div>
@stop

@section('content')

    {{-- BARRA DE FILTROS Y ACCIONES --}}
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body p-3">
            <form method="GET" action="{{ route('home') }}">
                <div class="row align-items-end">
                    <div class="col-md-4 col-lg-3 mb-2 mb-md-0">
                        <label class="small font-weight-bold text-uppercase text-muted mb-1">Fecha Inicio</label>
                        <div class="input-group input-group-alternative">
                            <div class="input-group-prepend">
                                <span class="input-group-text bg-light border-right-0"><i class="fas fa-calendar text-muted"></i></span>
                            </div>
                            <input type="date" name="fecha_inicio" value="{{ $fechaInicio }}" class="form-control border-left-0">
                        </div>
                    </div>
                    <div class="col-md-4 col-lg-3 mb-2 mb-md-0">
                        <label class="small font-weight-bold text-uppercase text-muted mb-1">Fecha Fin</label>
                        <div class="input-group input-group-alternative">
                            <div class="input-group-prepend">
                                <span class="input-group-text bg-light border-right-0"><i class="fas fa-calendar text-muted"></i></span>
                            </div>
                            <input type="date" name="fecha_fin" value="{{ $fechaFin }}" class="form-control border-left-0">
                        </div>
                    </div>
                    <div class="col-md-4 col-lg-6 d-flex align-items-center justify-content-md-end gap-2 mt-2 mt-md-0">
                        <button type="submit" class="btn btn-primary font-weight-bold px-4 mr-2">
                            <i class="fas fa-filter mr-1"></i> Filtrar
                        </button>
                        <a href="{{ route('home') }}" class="btn btn-outline-secondary mr-2" title="Limpiar filtro">
                            <i class="fas fa-sync-alt"></i>
                        </a>
                        <a href="{{ route('reportes.pdf', ['fecha_inicio' => $fechaInicio, 'fecha_fin' => $fechaFin]) }}" target="_blank" class="btn btn-danger font-weight-bold px-3">
                            <i class="fas fa-file-pdf mr-1"></i> Reporte PDF
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- TARJETAS DE MÉTRICAS GENERALES (KPIs) --}}
    <div class="row">
        {{-- Clientes --}}
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-0 shadow-sm h-100 metric-card border-left-info">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Clientes Registrados</div>
                            <div class="h3 mb-0 font-weight-bold text-dark">{{ $clientesRango->count() }}</div>
                        </div>
                        <div class="col-auto">
                            <div class="icon-shape bg-info-light text-info rounded-circle p-3">
                                <i class="fas fa-users fa-lg"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-transparent border-0 p-2 text-right">
                    <a href="{{ route('reportes.pdf.tipo', ['tipo' => 'clientes', 'fecha_inicio' => $fechaInicio, 'fecha_fin' => $fechaFin]) }}" target="_blank" class="small text-info font-weight-bold">
                        Exportar PDF <i class="fas fa-arrow-circle-right ml-1"></i>
                    </a>
                </div>
            </div>
        </div>

        {{-- Habitaciones Libres --}}
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-0 shadow-sm h-100 metric-card border-left-success">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Habitaciones Libres</div>
                            <div class="h3 mb-0 font-weight-bold text-dark">{{ $habitacionesDisponibles }}</div>
                        </div>
                        <div class="col-auto">
                            <div class="icon-shape bg-success-light text-success rounded-circle p-3">
                                <i class="fas fa-bed fa-lg"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-transparent border-0 p-2 text-right">
                    <a href="{{ route('reportes.pdf.tipo', ['tipo' => 'habitaciones', 'fecha_inicio' => $fechaInicio, 'fecha_fin' => $fechaFin]) }}" target="_blank" class="small text-success font-weight-bold">
                        Exportar PDF <i class="fas fa-arrow-circle-right ml-1"></i>
                    </a>
                </div>
            </div>
        </div>

        {{-- Monto en Pagos --}}
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-0 shadow-sm h-100 metric-card border-left-warning">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Total Recaudado</div>
                            <div class="h4 mb-0 font-weight-bold text-dark">Bs {{ number_format($pagosMonto, 2, ',', '.') }}</div>
                        </div>
                        <div class="col-auto">
                            <div class="icon-shape bg-warning-light text-warning rounded-circle p-3">
                                <i class="fas fa-wallet fa-lg"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-transparent border-0 p-2 text-right">
                    <a href="{{ route('reportes.pdf.tipo', ['tipo' => 'pagos', 'fecha_inicio' => $fechaInicio, 'fecha_fin' => $fechaFin]) }}" target="_blank" class="small text-warning font-weight-bold">
                        Exportar PDF <i class="fas fa-arrow-circle-right ml-1"></i>
                    </a>
                </div>
            </div>
        </div>

        {{-- Reservas Totales --}}
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-0 shadow-sm h-100 metric-card border-left-danger">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col">
                            <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">Total Reservas</div>
                            <div class="h3 mb-0 font-weight-bold text-dark">{{ $reservasTotal }}</div>
                        </div>
                        <div class="col-auto">
                            <div class="icon-shape bg-danger-light text-danger rounded-circle p-3">
                                <i class="fas fa-calendar-check fa-lg"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-transparent border-0 p-2 text-right">
                    <a href="{{ route('reportes.pdf.tipo', ['tipo' => 'reservas', 'fecha_inicio' => $fechaInicio, 'fecha_fin' => $fechaFin]) }}" target="_blank" class="small text-danger font-weight-bold">
                        Exportar PDF <i class="fas fa-arrow-circle-right ml-1"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>

    {{-- SECCIÓN RESUMEN DIARIO (HOY) --}}
    <div class="row mb-4">
        <div class="col-md-6 mb-3 mb-md-0">
            <div class="card border-0 shadow-sm bg-white h-100">
                <div class="card-body d-flex align-items-center justify-content-between">
                    <div>
                        <span class="badge badge-primary-soft text-primary font-weight-bold mb-1">HOY</span>
                        <h5 class="font-weight-bold text-dark mb-1">Pagos del día</h5>
                        <p class="text-muted small mb-0">{{ $pagosHoyCantidad }} transacción(es) realizada(s)</p>
                        <h3 class="text-success font-weight-bold mt-2 mb-0">Bs {{ number_format($pagosHoyMonto, 2, ',', '.') }}</h3>
                    </div>
                    <div class="text-right">
                        <a href="{{ route('reportes.pdf.tipo', ['tipo' => 'pagos', 'fecha_inicio' => $fechaInicio, 'fecha_fin' => $fechaFin]) }}" target="_blank" class="btn btn-sm btn-outline-danger font-weight-bold">
                            <i class="fas fa-file-pdf mr-1"></i> PDF
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card border-0 shadow-sm bg-white h-100">
                <div class="card-body d-flex align-items-center justify-content-between">
                    <div>
                        <span class="badge badge-secondary-soft text-secondary font-weight-bold mb-1">HOY</span>
                        <h5 class="font-weight-bold text-dark mb-1">Reservas del día</h5>
                        <p class="text-muted small mb-0">Ingresos o registros agendados para hoy</p>
                        <h3 class="text-dark font-weight-bold mt-2 mb-0">{{ $reservasHoyCantidad }} <span class="h6 text-muted font-weight-normal">reservas</span></h3>
                    </div>
                    <div class="text-right">
                        <a href="{{ route('reportes.pdf.tipo', ['tipo' => 'reservas', 'fecha_inicio' => $fechaInicio, 'fecha_fin' => $fechaFin]) }}" target="_blank" class="btn btn-sm btn-outline-danger font-weight-bold">
                            <i class="fas fa-file-pdf mr-1"></i> PDF
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- TABLAS DE DETALLE --}}
    <div class="row">
        {{-- Clientes --}}
        <div class="col-lg-6 mb-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white border-0 py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-dark">
                        <i class="fas fa-users text-info mr-2"></i>Detalle de Clientes
                    </h6>
                    <span class="badge badge-light text-muted">{{ $clientesRango->count() }} registros</span>
                </div>
                <div class="card-body p-0 table-responsive max-height-table">
                    <table class="table table-hover table-align-middle mb-0">
                        <thead class="thead-light">
                            <tr>
                                <th>Nombre</th>
                                <th>Carnet</th>
                                <th>Celular</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($clientesRango as $cliente)
                                <tr>
                                    <td class="font-weight-bold text-dark">{{ $cliente->nombre_completo }}</td>
                                    <td><code>{{ $cliente->carnet_identidad }}</code></td>
                                    <td>
                                        @if($cliente->celular)
                                            <span class="text-muted small"><i class="fas fa-phone mr-1"></i>{{ $cliente->celular }}</span>
                                        @else
                                            <span class="text-muted small">N/A</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="text-muted text-center py-4">
                                        <i class="fas fa-inbox fa-2x mb-2 d-block text-light"></i> No hay clientes en este rango.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- Habitaciones --}}
        <div class="col-lg-6 mb-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white border-0 py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-dark">
                        <i class="fas fa-bed text-success mr-2"></i>Estado de Habitaciones
                    </h6>
                </div>
                <div class="card-body p-0 table-responsive max-height-table">
                    <table class="table table-hover table-align-middle mb-0">
                        <thead class="thead-light">
                            <tr>
                                <th>Número</th>
                                <th>Piso</th>
                                <th>Tipo</th>
                                <th>Estado</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($habitacionesRango as $habitacion)
                                <tr>
                                    <td class="font-weight-bold">Hab. {{ $habitacion->numero }}</td>
                                    <td>Piso {{ $habitacion->piso }}</td>
                                    <td>{{ $habitacion->tipoHabitacion?->nombre ?? 'N/A' }}</td>
                                    <td>
                                        <span class="badge badge-pill badge-{{ $habitacion->estado == 'Disponible' ? 'success' : 'warning' }} px-3">
                                            {{ $habitacion->estado }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-muted text-center py-4">
                                        <i class="fas fa-inbox fa-2x mb-2 d-block text-light"></i> No hay habitaciones registradas.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- Pagos --}}
        <div class="col-lg-6 mb-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white border-0 py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-dark">
                        <i class="fas fa-wallet text-warning mr-2"></i>Últimos Pagos Registrados
                    </h6>
                </div>
                <div class="card-body p-0 table-responsive max-height-table">
                    <table class="table table-hover table-align-middle mb-0">
                        <thead class="thead-light">
                            <tr>
                                <th>Cliente</th>
                                <th>Monto</th>
                                <th>Método</th>
                                <th>Fecha</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($pagosRango as $pago)
                                <tr>
                                    <td class="text-truncate" style="max-width: 150px;">{{ $pago->reserva?->cliente?->nombre_completo ?? 'Sin cliente' }}</td>
                                    <td class="text-success font-weight-bold">Bs {{ number_format($pago->monto, 2, ',', '.') }}</td>
                                    <td><span class="badge badge-light border">{{ $pago->metodo_pago }}</span></td>
                                    <td class="text-muted small">{{ \Carbon\Carbon::parse($pago->fecha_pago)->format('d/m/Y') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-muted text-center py-4">
                                        <i class="fas fa-inbox fa-2x mb-2 d-block text-light"></i> No hay pagos registrados en este rango.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- Reservas --}}
        <div class="col-lg-6 mb-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white border-0 py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-dark">
                        <i class="fas fa-calendar-check text-danger mr-2"></i>Detalle de Reservas
                    </h6>
                </div>
                <div class="card-body p-0 table-responsive max-height-table">
                    <table class="table table-hover table-align-middle mb-0">
                        <thead class="thead-light">
                            <tr>
                                <th>Cliente</th>
                                <th>Hab.</th>
                                <th>Ingreso / Salida</th>
                                <th>Estado</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($reservasRango as $reserva)
                                <tr>
                                    <td class="text-truncate" style="max-width: 140px;">{{ $reserva->cliente?->nombre_completo ?? 'Sin cliente' }}</td>
                                    <td><span class="badge badge-light border">N° {{ $reserva->habitacion?->numero ?? 'N/A' }}</span></td>
                                    <td class="small text-muted">
                                        {{ \Carbon\Carbon::parse($reserva->fecha_ingreso)->format('d/m/Y') }} <br>
                                        <span class="text-xs text-secondary">al {{ \Carbon\Carbon::parse($reserva->fecha_salida)->format('d/m/Y') }}</span>
                                    </td>
                                    <td>
                                        <span class="badge badge-primary-soft text-primary">{{ $reserva->estado }}</span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-muted text-center py-4">
                                        <i class="fas fa-inbox fa-2x mb-2 d-block text-light"></i> No hay reservas en este rango.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@stop

@section('css')
    <style>
        /* Card Borde Izquierdo */
        .border-left-info { border-left: 4px solid #17a2b8 !important; }
        .border-left-success { border-left: 4px solid #28a745 !important; }
        .border-left-warning { border-left: 4px solid #ffc107 !important; }
        .border-left-danger { border-left: 4px solid #dc3545 !important; }

        /* Icon Shapes */
        .icon-shape {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 48px;
            height: 48px;
        }
        .bg-info-light { background-color: rgba(23, 162, 184, 0.15); }
        .bg-success-light { background-color: rgba(40, 167, 69, 0.15); }
        .bg-warning-light { background-color: rgba(255, 193, 7, 0.15); }
        .bg-danger-light { background-color: rgba(220, 53, 69, 0.15); }

        /* Soft Badges */
        .badge-primary-soft { background-color: rgba(0, 123, 255, 0.12); color: #0056b3; }
        .badge-secondary-soft { background-color: rgba(108, 117, 125, 0.12); color: #495057; }

        /* Micro Interacción Hover en Tarjetas */
        .metric-card {
            transition: transform 0.2s ease, shadow 0.2s ease;
        }
        .metric-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 .5rem 1rem rgba(0,0,0,.08) !important;
        }

        /* Estilos de Tabla */
        .table-align-middle td, .table-align-middle th {
            vertical-align: middle;
        }
        .max-height-table {
            max-height: 380px;
            overflow-y: auto;
        }
    </style>
@stop

@section('js')
    <script>
        // Opcional: scripts adicionales o inicialización de componentes
    </script>
@stop