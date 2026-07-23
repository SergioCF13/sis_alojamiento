@extends('adminlte::page')

@section('title', 'Detalle de Pago')

@section('content_header')
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Detalle de Pago</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('pagos.index') }}">Pagos</a></li>
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
            <h3 class="card-title"><i class="fas fa-money-bill-wave mr-1"></i> Pago #{{ $pago->id }}</h3>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <p><strong>Reserva:</strong> #{{ $pago->reserva_id }}</p>
                    <p><strong>Cliente:</strong> {{ $pago->reserva?->cliente?->nombre_completo ?? 'Sin cliente' }}</p>
                    <p><strong>Monto:</strong> Bs {{ number_format($pago->monto, 2) }}</p>
                </div>
                <div class="col-md-6">
                    <p><strong>Método de pago:</strong> {{ $pago->metodo_pago }}</p>
                    <p><strong>Estado:</strong> {{ $pago->estado }}</p>
                    <p><strong>Fecha:</strong> {{ $pago->fecha_pago }}</p>
                    <p><strong>Observaciones:</strong> {{ $pago->observaciones ?? '-' }}</p>
                </div>
            </div>
        </div>
        <div class="card-footer">
            <a href="{{ route('pagos.index') }}" class="btn btn-secondary"><i class="fas fa-arrow-left mr-1"></i>Volver</a>
            <a href="{{ route('pagos.edit', $pago) }}" class="btn btn-warning"><i class="fas fa-edit mr-1"></i>Editar</a>
        </div>
    </div>
</div>
@endsection
