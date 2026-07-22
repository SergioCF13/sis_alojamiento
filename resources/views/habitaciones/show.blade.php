@extends('adminlte::page')

@section('title', 'Detalle de Habitación')

@section('content_header')
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Detalles de Habitación N° {{ $habitacion->numero }}</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Inicio</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('habitaciones.index') }}">Habitaciones</a></li>
                    <li class="breadcrumb-item active">Detalle</li>
                </ol>
            </div>
        </div>
    </div>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card card-info card-outline">
                <div class="card-header">
                    <h3 class="card-title"><i class="fas fa-info-circle mr-1"></i> Ficha de Información</h3>
                </div>

                <div class="card-body">
                    <ul class="list-group list-group-unbordered mb-3">
                        <li class="list-group-item">
                            <b><i class="fas fa-hashtag text-muted mr-1"></i> Número:</b> 
                            <span class="float-right font-weight-bold text-dark">{{ $habitacion->numero }}</span>
                        </li>
                        <li class="list-group-item">
                            <b><i class="fas fa-layer-group text-muted mr-1"></i> Ubicación:</b> 
                            <span class="float-right font-weight-bold text-dark">Piso {{ $habitacion->piso }}</span>
                        </li>
                        <li class="list-group-item">
                            <b><i class="fas fa-bed text-muted mr-1"></i> Tipo de Habitación:</b> 
                            <span class="float-right text-dark">
                                {{ $habitacion->tipoHabitacion->nombre ?? 'Sin tipo' }}
                            </span>
                        </li>
                        <li class="list-group-item">
                            <b><i class="fas fa-tag text-muted mr-1"></i> Precio por Noche:</b> 
                            <span class="float-right text-success font-weight-bold">
                                Bs {{ number_format($habitacion->tipoHabitacion->precio ?? 0, 2) }}
                            </span>
                        </li>
                        <li class="list-group-item border-bottom-0">
                            <b><i class="fas fa-flag text-muted mr-1"></i> Estado Actual:</b>
                            <span class="float-right">
                                @switch($habitacion->estado)
                                    @case('Disponible')
                                        <span class="badge badge-success px-2 py-1">Disponible</span>
                                        @break
                                    @case('Ocupada')
                                        <span class="badge badge-danger px-2 py-1">Ocupada</span>
                                        @break
                                    @case('Limpieza')
                                        <span class="badge badge-warning px-2 py-1">Limpieza</span>
                                        @break
                                    @case('Mantenimiento')
                                        <span class="badge badge-secondary px-2 py-1">Mantenimiento</span>
                                        @break
                                    @default
                                        <span class="badge badge-info px-2 py-1">{{ $habitacion->estado }}</span>
                                @endswitch
                            </span>
                        </li>
                    </ul>

                    @if($habitacion->tipoHabitacion && $habitacion->tipoHabitacion->descripcion)
                        <div class="callout callout-info mt-3 mb-0">
                            <h5><i class="fas fa-align-left mr-1"></i> Descripción del Tipo:</h5>
                            <p class="text-muted mb-0">{{ $habitacion->tipoHabitacion->descripcion }}</p>
                        </div>
                    @endif
                </div>

                <div class="card-footer d-flex justify-content-between">
                    <a href="{{ route('habitaciones.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left mr-1"></i> Volver al listado
                    </a>
                    <a href="{{ route('habitaciones.edit', $habitacion->id) }}" class="btn btn-warning">
                        <i class="fas fa-pencil-alt mr-1"></i> Editar
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection