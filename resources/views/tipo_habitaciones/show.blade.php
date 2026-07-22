@extends('adminlte::page')

@section('title', 'Detalle de Tipo de Habitación')

@section('content_header')
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Detalles de Tipo de Habitación</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Inicio</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('tipo_habitaciones.index') }}">Tipos de Habitación</a></li>
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
                            <b><i class="fas fa-tag text-muted mr-1"></i> Nombre:</b> 
                            <span class="float-right font-weight-bold text-dark">{{ $tipoHabitacion->nombre }}</span>
                        </li>
                        <li class="list-group-item">
                            <b><i class="fas fa-money-bill-wave text-muted mr-1"></i> Precio Base:</b> 
                            <span class="float-right badge badge-success p-2">Bs {{ number_format($tipoHabitacion->precio, 2) }}</span>
                        </li>
                        <li class="list-group-item border-bottom-0">
                            <b><i class="fas fa-align-left text-muted mr-1"></i> Descripción:</b>
                            <p class="text-muted mt-2 mb-0">
                                {{ $tipoHabitacion->descripcion ?? 'Sin descripción especificada.' }}
                            </p>
                        </li>
                    </ul>
                </div>

                <div class="card-footer d-flex justify-content-between">
                    <a href="{{ route('tipo_habitaciones.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left mr-1"></i> Volver al listado
                    </a>
                    <a href="{{ route('tipo_habitaciones.edit', $tipoHabitacion) }}" class="btn btn-warning">
                        <i class="fas fa-pencil-alt mr-1"></i> Editar
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection