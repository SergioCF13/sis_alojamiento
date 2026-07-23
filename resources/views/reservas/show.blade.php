@extends('adminlte::page')

@section('title', 'Detalle de Reserva')

@section('content_header')
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Detalle de Reserva</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('reservas.index') }}">Reservas</a></li>
                    <li class="breadcrumb-item active">Detalle</li>
                </ol>
            </div>
        </div>
    </div>
@endsection

@section('content')
<div class="container-fluid">
    <div class="card card-info card-outline">
        <div class="card-header">
            <h3 class="card-title"><i class="fas fa-calendar-check mr-1"></i> Reserva #{{ $reserva->id }}</h3>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <p><strong>Cliente:</strong> {{ $reserva->cliente?->nombre_completo ?? 'Sin cliente' }}</p>
                    <p><strong>Habitación:</strong> {{ $reserva->habitacion?->numero ?? 'Sin habitación asignada' }}</p>
                    <p><strong>Fecha ingreso:</strong> {{ $reserva->fecha_ingreso }} {{ $reserva->hora_ingreso }}</p>
                    <p><strong>Fecha salida:</strong> {{ $reserva->fecha_salida }} {{ $reserva->hora_salida }}</p>
                </div>
                <div class="col-md-6">
                    <p><strong>Cantidad de personas:</strong> {{ $reserva->cantidad_persona }}</p>
                    <p><strong>Estado:</strong> {{ $reserva->estado }}</p>
                    <p><strong>Observaciones:</strong> {{ $reserva->observaciones ?? '-' }}</p>
                </div>
            </div>

            <hr>

            <div class="row">
                <div class="col-md-4">
                    <div class="info-box bg-light">
                        <span class="info-box-icon bg-primary"><i class="fas fa-tag"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Monto total</span>
                            <span class="info-box-number">Bs {{ number_format($montoTotal, 2) }}</span>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="info-box bg-light">
                        <span class="info-box-icon bg-success"><i class="fas fa-check-circle"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Monto pagado</span>
                            <span class="info-box-number">Bs {{ number_format($montoPagado, 2) }}</span>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="info-box bg-light">
                        <span class="info-box-icon bg-warning"><i class="fas fa-exclamation-triangle"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Saldo pendiente</span>
                            <span class="info-box-number">Bs {{ number_format($saldoPendiente, 2) }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mt-3">
                <a href="{{ route('pagos.create') }}?reserva_id={{ $reserva->id }}" class="btn btn-success">
                    <i class="fas fa-money-bill-wave mr-1"></i> Registrar pago
                </a>
            </div>
        </div>
        <div class="card-footer">
            <a href="{{ route('reservas.index') }}" class="btn btn-secondary"><i class="fas fa-arrow-left mr-1"></i>Volver</a>
            <a href="{{ route('reservas.edit', $reserva) }}" class="btn btn-warning"><i class="fas fa-edit mr-1"></i>Editar</a>
        </div>
    </div>
</div>
@endsection
