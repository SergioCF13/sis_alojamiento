@extends('adminlte::page')

@section('title', 'Analítica y Reportes')

@section('css')
<style>
    /* Estilos del Dashboard Ejecutivo */
    .metric-card {
        background: #ffffff;
        border: 1px solid #e9ecef;
        border-radius: 12px;
        transition: all 0.25s ease;
        position: relative;
        overflow: hidden;
    }
    .metric-card:hover {
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.05);
        transform: translateY(-2px);
    }
    .metric-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 4px;
        height: 100%;
    }
    .metric-primary::before { background-color: #3b82f6; }
    .metric-success::before { background-color: #10b981; }
    .metric-warning::before { background-color: #f59e0b; }
    .metric-danger::before  { background-color: #ef4444; }

    .metric-icon {
        width: 46px;
        height: 46px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.25rem;
    }
    
    .card-executive {
        border: 1px solid #e9ecef;
        border-radius: 12px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.02);
        background: #ffffff;
    }
    .card-executive .card-header {
        background: transparent;
        border-bottom: 1px solid #f1f5f9;
        padding: 1.1rem 1.25rem;
    }
    
    .status-badge {
        font-size: 0.8rem;
        padding: 0.35em 0.75em;
        border-radius: 20px;
        font-weight: 600;
    }
</style>
@endsection

@section('content_header')
    <div class="container-fluid pt-2">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <div>
                <h3 class="font-weight-bold text-dark m-0">Consola de Métricas</h3>
                <small class="text-muted">Visión general del estado financiero y operativo</small>
            </div>
            <div>
                <button class="btn btn-outline-secondary btn-sm rounded-pill px-3 mr-2" onclick="window.print()">
                    <i class="fas fa-print mr-1"></i> Imprimir
                </button>
                <a href="{{ route('home') }}" class="btn btn-light btn-sm rounded-pill px-3">
                    <i class="fas fa-home mr-1"></i> Inicio
                </a>
            </div>
        </div>
    </div>
@endsection

@section('content')
<div class="container-fluid">
    {{-- Tarjetas KPI Estilo SaaS --}}
    <div class="row">
        <div class="col-xl-3 col-md-6 mb-3">
            <div class="metric-card metric-primary p-3">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <span class="text-xs font-weight-bold text-uppercase text-muted">Ingresos Hoy</span>
                        <h4 class="font-weight-bold text-dark mb-0 mt-1">Bs {{ number_format($pagosHoy, 2, ',', '.') }}</h4>
                    </div>
                    <div class="metric-icon bg-light text-primary">
                        <i class="fas fa-wallet"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-3">
            <div class="metric-card metric-success p-3">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <span class="text-xs font-weight-bold text-uppercase text-muted">Ingresos del Mes</span>
                        <h4 class="font-weight-bold text-dark mb-0 mt-1">Bs {{ number_format($pagosMes, 2, ',', '.') }}</h4>
                    </div>
                    <div class="metric-icon bg-light text-success">
                        <i class="fas fa-chart-line"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-3">
            <div class="metric-card metric-warning p-3">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <span class="text-xs font-weight-bold text-uppercase text-muted">Reservas Totales</span>
                        <h4 class="font-weight-bold text-dark mb-0 mt-1">{{ $totalReservas }}</h4>
                    </div>
                    <div class="metric-icon bg-light text-warning">
                        <i class="fas fa-calendar-check"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-3">
            <div class="metric-card metric-danger p-3">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <span class="text-xs font-weight-bold text-uppercase text-muted">Ocupación Actual</span>
                        <h4 class="font-weight-bold text-dark mb-0 mt-1">{{ $habitacionesOcupadas }} hab.</h4>
                    </div>
                    <div class="metric-icon bg-light text-danger">
                        <i class="fas fa-bed"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Bloque Principal: Tendencia de Pagos y Métodos --}}
    <div class="row">
        <div class="col-lg-8 mb-4">
            <div class="card card-executive h-100">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="m-0 font-weight-bold text-dark text-sm"><i class="fas fa-chart-area mr-2 text-primary"></i>Evolución de Pagos (7 días)</h5>
                    <span class="badge badge-light text-muted border">Última semana</span>
                </div>
                <div class="card-body">
                    <canvas id="chartPagos7Dias" height="100"></canvas>
                </div>
            </div>
        </div>

        <div class="col-lg-4 mb-4">
            <div class="card card-executive h-100">
                <div class="card-header">
                    <h5 class="m-0 font-weight-bold text-dark text-sm"><i class="fas fa-pie-chart mr-2 text-info"></i>Métodos de Pago</h5>
                </div>
                <div class="card-body d-flex align-items-center justify-content-center">
                    <div style="width: 100%; max-width: 280px;">
                        <canvas id="chartPagosMetodo" height="200"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Bloque Secundario: Estado de Reservas y Tabla Resumen --}}
    <div class="row">
        <div class="col-lg-4 mb-4">
            <div class="card card-executive h-100">
                <div class="card-header">
                    <h5 class="m-0 font-weight-bold text-dark text-sm"><i class="fas fa-ring mr-2 text-warning"></i>Estado de Reservas</h5>
                </div>
                <div class="card-body d-flex align-items-center justify-content-center">
                    <div style="width: 100%; max-width: 250px;">
                        <canvas id="chartReservasEstado" height="200"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4 mb-4">
            <div class="card card-executive h-100">
                <div class="card-header">
                    <h5 class="m-0 font-weight-bold text-dark text-sm"><i class="fas fa-list-ul mr-2 text-secondary"></i>Detalle Operativo</h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <tbody>
                                <tr>
                                    <td class="text-muted"><i class="fas fa-file-invoice text-primary mr-2"></i> Pagos Registrados</td>
                                    <td class="text-right font-weight-bold text-dark">{{ $totalPagos }}</td>
                                </tr>
                                <tr>
                                    <td class="text-muted"><i class="fas fa-door-open text-success mr-2"></i> Hab. Disponibles</td>
                                    <td class="text-right font-weight-bold text-success">{{ $habitacionesDisponibles }}</td>
                                </tr>
                                <tr>
                                    <td class="text-muted"><i class="fas fa-door-closed text-danger mr-2"></i> Hab. Ocupadas</td>
                                    <td class="text-right font-weight-bold text-danger">{{ $habitacionesOcupadas }}</td>
                                </tr>
                                <tr>
                                    <td class="text-muted"><i class="fas fa-clock text-warning mr-2"></i> Reservas Activas</td>
                                    <td class="text-right font-weight-bold text-dark">{{ $reservasPorEstado['Confirmada'] ?? 0 }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4 mb-4">
            <div class="card card-executive h-100">
                <div class="card-header">
                    <h5 class="m-0 font-weight-bold text-dark text-sm"><i class="fas fa-hotel mr-2 text-success"></i>Estado de Habitaciones</h5>
                </div>
                <div class="card-body">
                    <canvas id="chartHabitacionesEstado" height="170"></canvas>
                </div>
            </div>
        </div>
    </div>

    {{-- Bloque Inferior: Clientes Frecuentes --}}
    <div class="row">
        <div class="col-12 mb-4">
            <div class="card card-executive">
                <div class="card-header">
                    <h5 class="m-0 font-weight-bold text-dark text-sm"><i class="fas fa-users mr-2 text-primary"></i>Top Clientes con Mayor Número de Reservas</h5>
                </div>
                <div class="card-body">
                    <canvas id="chartClientesReservas" height="60"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('js')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    Chart.defaults.font.family = "'Inter', '-apple-system', 'Segoe UI', 'Roboto', sans-serif";
    Chart.defaults.color = '#64748b';

    const palette = {
        blue: '#3b82f6',
        teal: '#10b981',
        amber: '#f59e0b',
        rose: '#ef4444',
        purple: '#8b5cf6',
        slate: '#64748b'
    };

    // 1. Pagos 7 días (Línea con relleno degradado)
    const ctxPagos = document.getElementById('chartPagos7Dias').getContext('2d');
    const gradientPagos = ctxPagos.createLinearGradient(0, 0, 0, 300);
    gradientPagos.addColorStop(0, 'rgba(59, 130, 246, 0.35)');
    gradientPagos.addColorStop(1, 'rgba(59, 130, 246, 0.0)');

    new Chart(ctxPagos, {
        type: 'line',
        data: {
            labels: {!! json_encode($pagosUltimos7Dias->pluck('fecha')) !!},
            datasets: [{
                label: 'Pagos (Bs)',
                data: {!! json_encode($pagosUltimos7Dias->pluck('total')) !!},
                borderColor: palette.blue,
                borderWidth: 3,
                backgroundColor: gradientPagos,
                fill: true,
                tension: 0.35,
                pointRadius: 4,
                pointHoverRadius: 6,
                pointBackgroundColor: palette.blue
            }]
        },
        options: {
            responsive: true,
            plugins: { legend: { display: false } },
            scales: {
                y: { grid: { color: '#f1f5f9' }, border: { dash: [4, 4] } },
                x: { grid: { display: false } }
            }
        }
    });

    // 2. Métodos de Pago
    new Chart(document.getElementById('chartPagosMetodo'), {
        type: 'doughnut',
        data: {
            labels: {!! json_encode(array_keys($pagosPorMetodo)) !!},
            datasets: [{
                data: {!! json_encode(array_values($pagosPorMetodo)) !!},
                backgroundColor: [palette.blue, palette.teal, palette.amber, palette.purple, palette.rose],
                borderWidth: 0
            }]
        },
        options: {
            responsive: true,
            plugins: { legend: { position: 'bottom', labels: { boxWidth: 12 } } },
            cutout: '70%'
        }
    });

    // 3. Reservas por Estado
    new Chart(document.getElementById('chartReservasEstado'), {
        type: 'doughnut',
        data: {
            labels: {!! json_encode(array_keys($reservasPorEstado)) !!},
            datasets: [{
                data: {!! json_encode(array_values($reservasPorEstado)) !!},
                backgroundColor: [palette.blue, palette.teal, palette.amber, palette.rose],
                borderWidth: 0
            }]
        },
        options: {
            responsive: true,
            plugins: { legend: { position: 'bottom', labels: { boxWidth: 12 } } },
            cutout: '65%'
        }
    });

    // 4. Habitaciones por Estado
    new Chart(document.getElementById('chartHabitacionesEstado'), {
        type: 'bar',
        data: {
            labels: {!! json_encode(array_keys($habitacionesPorEstado)) !!},
            datasets: [{
                data: {!! json_encode(array_values($habitacionesPorEstado)) !!},
                backgroundColor: [palette.teal, palette.amber, palette.blue, palette.rose],
                borderRadius: 8,
                maxBarThickness: 32
            }]
        },
        options: {
            responsive: true,
            plugins: { legend: { display: false } },
            scales: {
                y: { grid: { color: '#f1f5f9' }, ticks: { stepSize: 1 } },
                x: { grid: { display: false } }
            }
        }
    });

    // 5. Clientes Top
    new Chart(document.getElementById('chartClientesReservas'), {
        type: 'bar',
        data: {
            labels: {!! json_encode($clientesTop->pluck('cliente.nombre')) !!},
            datasets: [{
                label: 'Reservas',
                data: {!! json_encode($clientesTop->pluck('total_reservas')) !!},
                backgroundColor: palette.blue,
                borderRadius: 6,
                maxBarThickness: 28
            }]
        },
        options: {
            responsive: true,
            plugins: { legend: { display: false } },
            scales: {
                y: { grid: { color: '#f1f5f9' }, ticks: { stepSize: 1 } },
                x: { grid: { display: false } }
            }
        }
    });
</script>
@endsection