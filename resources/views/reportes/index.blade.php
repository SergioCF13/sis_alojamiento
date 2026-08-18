@extends('adminlte::page')

@section('title', 'Analítica y Reportes')

@section('css')
<style>
    :root {
        --bg-card: #ffffff;
        --border-color: #e2e8f0;
        --text-primary: #0f172a;
        --text-secondary: #64748b;
        --primary-accent: #2563eb;
    }

    body {
        background-color: #f8fafc !important;
    }

    /* Header & Filter Card */
    .dashboard-header-title {
        color: var(--text-primary);
        font-weight: 700;
        letter-spacing: -0.02em;
    }

    .filter-card {
        background: #ffffff;
        border: 1px solid var(--border-color);
        border-radius: 16px;
        box-shadow: 0 4px 20px -2px rgba(15, 23, 42, 0.05);
    }

    .form-control-custom {
        border-radius: 10px;
        border: 1px solid #cbd5e1;
        padding: 0.5rem 0.85rem;
        font-size: 0.875rem;
        transition: all 0.2s;
    }
    .form-control-custom:focus {
        border-color: var(--primary-accent);
        box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.15);
    }

    /* Metric Cards Redesign */
    .kpi-card {
        background: #ffffff;
        border: 1px solid var(--border-color);
        border-radius: 16px;
        padding: 1.25rem;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        position: relative;
    }
    .kpi-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 12px 24px -4px rgba(15, 23, 42, 0.08);
    }

    .kpi-icon-wrapper {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.2rem;
    }

    /* Color Variances for KPI Cards */
    .kpi-primary .kpi-icon-wrapper { background: #eff6ff; color: #2563eb; }
    .kpi-success .kpi-icon-wrapper { background: #ecfdf5; color: #10b981; }
    .kpi-warning .kpi-icon-wrapper { background: #fffbeb; color: #f59e0b; }
    .kpi-danger .kpi-icon-wrapper  { background: #fef2f2; color: #ef4444; }

    /* Compact Day Cards */
    .mini-kpi-card {
        background: #ffffff;
        border: 1px solid var(--border-color);
        border-radius: 12px;
        padding: 1rem;
        text-align: center;
    }

    /* Executive Cards (Charts & Tables) */
    .card-executive {
        border: 1px solid var(--border-color);
        border-radius: 16px;
        box-shadow: 0 4px 15px -2px rgba(0, 0, 0, 0.03);
        background: #ffffff;
    }
    .card-executive .card-header {
        background: transparent;
        border-bottom: 1px solid #f1f5f9;
        padding: 1.25rem 1.5rem;
    }

    /* Table Styling */
    .table-custom tbody tr td {
        padding: 0.85rem 1.25rem;
        font-size: 0.875rem;
        border-color: #f1f5f9;
    }
</style>
@endsection

@section('content_header')
<div class="container-fluid pt-3">
    <!-- Top Action Bar -->
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
        <div>
            <h2 class="dashboard-header-title m-0">Consola de Métricas</h2>
            <p class="text-muted text-sm m-0">Monitoreo en tiempo real del rendimiento financiero y reservas</p>
        </div>
        <div class="d-flex align-items-center gap-2">
            <button class="btn btn-outline-secondary btn-sm rounded-pill px-3 shadow-sm" onclick="window.print()">
                <i class="fas fa-print mr-1"></i> Imprimir
            </button>
            <a href="{{ route('home') }}" class="btn btn-light btn-sm rounded-pill px-3 border shadow-sm">
                <i class="fas fa-home mr-1"></i> Inicio
            </a>
        </div>
    </div>

    <!-- Filter Bar -->
    <div class="card filter-card mb-4">
        <div class="card-body p-3">
            <form method="GET" action="{{ route('reportes.index') }}" class="row align-items-end g-3">
                <div class="col-md-4">
                    <label class="form-label text-xs font-weight-bold text-uppercase text-muted">Fecha Inicio</label>
                    <input type="date" name="fecha_inicio" class="form-control form-control-custom" value="{{ $fechaInicio ?? '' }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label text-xs font-weight-bold text-uppercase text-muted">Fecha Fin</label>
                    <input type="date" name="fecha_fin" class="form-control form-control-custom" value="{{ $fechaFin ?? '' }}">
                </div>
                <div class="col-md-4 d-flex gap-2">
                    <button type="submit" class="btn btn-primary btn-sm rounded-pill px-4 shadow-sm w-100">
                        <i class="fas fa-filter mr-1"></i> Aplicar Filtro
                    </button>
                    <a href="{{ route('reportes.pdf', ['fecha_inicio' => $fechaInicio, 'fecha_fin' => $fechaFin]) }}" class="btn btn-outline-danger btn-sm rounded-pill px-4 shadow-sm" target="_blank">
                        <i class="fas fa-file-pdf mr-1"></i> PDF
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('content')
<div class="container-fluid">
    @php
        $rangoActual = \Carbon\Carbon::parse($fechaInicio)->translatedFormat('d/m/Y') . ' - ' . \Carbon\Carbon::parse($fechaFin)->translatedFormat('d/m/Y');
    @endphp

    <div class="d-flex align-items-center mb-3">
        <span class="badge bg-white text-dark border px-3 py-2 rounded-pill shadow-sm">
            <i class="far fa-calendar-alt text-primary mr-2"></i><strong>Periodo evaluado:</strong> {{ $rangoActual }}
        </span>
    </div>

    <!-- Main KPIs -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-3 mb-xl-0">
            <div class="kpi-card kpi-primary">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <span class="text-xs font-weight-bold text-uppercase text-muted">Ingresos Rango</span>
                        <h3 class="font-weight-bold text-dark mt-2 mb-0">Bs {{ number_format($montoPagos ?? 0, 2, ',', '.') }}</h3>
                    </div>
                    <div class="kpi-icon-wrapper">
                        <i class="fas fa-wallet"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-3 mb-xl-0">
            <div class="kpi-card kpi-success">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <span class="text-xs font-weight-bold text-uppercase text-muted">Ingresos del Mes</span>
                        <h3 class="font-weight-bold text-dark mt-2 mb-0">Bs {{ number_format($pagosMes, 2, ',', '.') }}</h3>
                    </div>
                    <div class="kpi-icon-wrapper">
                        <i class="fas fa-chart-line"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-3 mb-xl-0">
            <div class="kpi-card kpi-warning">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <span class="text-xs font-weight-bold text-uppercase text-muted">Reservas Rango</span>
                        <h3 class="font-weight-bold text-dark mt-2 mb-0">{{ $totalReservas }}</h3>
                    </div>
                    <div class="kpi-icon-wrapper">
                        <i class="fas fa-calendar-check"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-3 mb-xl-0">
            <div class="kpi-card kpi-danger">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <span class="text-xs font-weight-bold text-uppercase text-muted">Clientes Únicos</span>
                        <h3 class="font-weight-bold text-dark mt-2 mb-0">{{ $clientesEnRango }}</h3>
                    </div>
                    <div class="kpi-icon-wrapper">
                        <i class="fas fa-users"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Daily Quick Stats -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="mini-kpi-card">
                <div class="text-muted text-uppercase text-xs font-weight-bold">Pagos Hoy</div>
                <h5 class="font-weight-bold mb-0 mt-1">{{ $pagosDelDiaCantidad ?? 0 }}</h5>
                <span class="badge bg-success-soft text-success text-xs mt-1">Bs {{ number_format($pagosDelDiaMonto ?? 0, 2, ',', '.') }}</span>
                <div class="mt-2">
                    <a href="{{ route('reportes.pdf.tipo', ['tipo' => 'pagos', 'fecha_inicio' => $fechaInicio, 'fecha_fin' => $fechaFin]) }}" target="_blank" class="btn btn-xs btn-outline-danger btn-block">
                        <i class="fas fa-file-pdf mr-1"></i> PDF
                    </a>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="mini-kpi-card">
                <div class="text-muted text-uppercase text-xs font-weight-bold">Reservas Hoy</div>
                <h5 class="font-weight-bold mb-0 mt-1">{{ $reservasDelDia ?? 0 }}</h5>
                <div class="mt-2">
                    <a href="{{ route('reportes.pdf.tipo', ['tipo' => 'reservas', 'fecha_inicio' => $fechaInicio, 'fecha_fin' => $fechaFin]) }}" target="_blank" class="btn btn-xs btn-outline-danger btn-block">
                        <i class="fas fa-file-pdf mr-1"></i> PDF
                    </a>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="mini-kpi-card">
                <div class="text-muted text-uppercase text-xs font-weight-bold">Total Transacciones</div>
                <h5 class="font-weight-bold mb-0 mt-1">{{ $totalPagos }}</h5>
            </div>
        </div>
        <div class="col-md-3">
            <div class="mini-kpi-card">
                <div class="text-muted text-uppercase text-xs font-weight-bold">Habitaciones Ocupadas</div>
                <h5 class="font-weight-bold mb-0 mt-1">{{ $habitacionesEnRango }}</h5>
            </div>
        </div>
    </div>

    <!-- Charts Section: Main -->
    <div class="row">
        <div class="col-lg-8 mb-4">
            <div class="card card-executive h-100">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-dark"><i class="fas fa-chart-area mr-2 text-primary"></i>Evolución de Pagos (Últimos 7 días)</h6>
                </div>
                <div class="card-body">
                    <canvas id="chartPagos7Dias" height="100"></canvas>
                </div>
            </div>
        </div>

        <div class="col-lg-4 mb-4">
            <div class="card card-executive h-100">
                <div class="card-header">
                    <h6 class="m-0 font-weight-bold text-dark"><i class="fas fa-chart-pie mr-2 text-info"></i>Métodos de Pago</h6>
                </div>
                <div class="card-body d-flex align-items-center justify-content-center">
                    <div style="width: 100%; max-width: 260px;">
                        <canvas id="chartPagosMetodo" height="200"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Section: Secondary -->
    <div class="row">
        <div class="col-lg-4 mb-4">
            <div class="card card-executive h-100">
                <div class="card-header">
                    <h6 class="m-0 font-weight-bold text-dark"><i class="fas fa-chart-donut mr-2 text-warning"></i>Estado de Reservas</h6>
                </div>
                <div class="card-body d-flex align-items-center justify-content-center">
                    <div style="width: 100%; max-width: 240px;">
                        <canvas id="chartReservasEstado" height="200"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4 mb-4">
            <div class="card card-executive h-100">
                <div class="card-header">
                    <h6 class="m-0 font-weight-bold text-dark"><i class="fas fa-list-alt mr-2 text-secondary"></i>Detalle Operativo</h6>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-custom align-middle mb-0">
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
                    <h6 class="m-0 font-weight-bold text-dark"><i class="fas fa-hotel mr-2 text-success"></i>Estado de Habitaciones</h6>
                </div>
                <div class="card-body">
                    <canvas id="chartHabitacionesEstado" height="170"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Bottom Section: Top Clients -->
    <div class="row">
        <div class="col-12 mb-4">
            <div class="card card-executive">
                <div class="card-header">
                    <h6 class="m-0 font-weight-bold text-dark"><i class="fas fa-user-star mr-2 text-primary"></i>Top Clientes Frecuentes</h6>
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
        blue: '#2563eb',
        teal: '#10b981',
        amber: '#f59e0b',
        rose: '#ef4444',
        purple: '#8b5cf6',
        slate: '#64748b'
    };

    // 1. Pagos 7 días
    const ctxPagos = document.getElementById('chartPagos7Dias').getContext('2d');
    const gradientPagos = ctxPagos.createLinearGradient(0, 0, 0, 300);
    gradientPagos.addColorStop(0, 'rgba(37, 99, 235, 0.25)');
    gradientPagos.addColorStop(1, 'rgba(37, 99, 235, 0.0)');

    new Chart(ctxPagos, {
        type: 'line',
        data: {
            labels: {!! json_encode($pagosUltimos7Dias->pluck('fecha')) !!},
            datasets: [{
                label: 'Pagos (Bs)',
                data: {!! json_encode($pagosUltimos7Dias->pluck('total')) !!},
                borderColor: palette.blue,
                borderWidth: 2.5,
                backgroundColor: gradientPagos,
                fill: true,
                tension: 0.4,
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
            plugins: { legend: { position: 'bottom', labels: { boxWidth: 10, usePointStyle: true } } },
            cutout: '75%'
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
            plugins: { legend: { position: 'bottom', labels: { boxWidth: 10, usePointStyle: true } } },
            cutout: '70%'
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
                maxBarThickness: 24
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