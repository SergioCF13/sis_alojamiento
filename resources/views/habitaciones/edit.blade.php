@extends('adminlte::page')

@section('title', 'Editar Habitación')

@section('content_header')
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Editar Habitación N° {{ $habitacion->numero }}</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Inicio</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('habitaciones.index') }}">Habitaciones</a></li>
                    <li class="breadcrumb-item active">Editar</li>
                </ol>
            </div>
        </div>
    </div>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card card-warning card-outline">
                <div class="card-header">
                    <h3 class="card-title"><i class="fas fa-pencil-alt mr-1"></i> Modificar Datos</h3>
                </div>

                <form action="{{ route('habitaciones.update', $habitacion->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="card-body">
                        <div class="row">
                            <!-- Número -->
                            <div class="col-md-6 form-group">
                                <label for="numero">Número de Habitación <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-hashtag"></i></span>
                                    </div>
                                    <input type="text" name="numero" id="numero" class="form-control @error('numero') is-invalid @enderror" value="{{ old('numero', $habitacion->numero) }}" required>
                                </div>
                                @error('numero')
                                    <span class="invalid-feedback d-block">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- Piso -->
                            <div class="col-md-6 form-group">
                                <label for="piso">Piso <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-layer-group"></i></span>
                                    </div>
                                    <input type="number" min="1" name="piso" id="piso" class="form-control @error('piso') is-invalid @enderror" value="{{ old('piso', $habitacion->piso) }}" required>
                                </div>
                                @error('piso')
                                    <span class="invalid-feedback d-block">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <!-- Tipo de Habitación -->
                            <div class="col-md-6 form-group">
                                <label for="tipo_habitacion_id">Tipo de Habitación <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-bed"></i></span>
                                    </div>
                                    <select name="tipo_habitacion_id" id="tipo_habitacion_id" class="form-control @error('tipo_habitacion_id') is-invalid @enderror" required>
                                        <option value="">-- Seleccionar Tipo --</option>
                                        @foreach($tipoHabitaciones as $tipo)
                                            <option value="{{ $tipo->id }}" {{ old('tipo_habitacion_id', $habitacion->tipo_habitacion_id) == $tipo->id ? 'selected' : '' }}>
                                                {{ $tipo->nombre }} (Bs {{ number_format($tipo->precio, 2) }})
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                @error('tipo_habitacion_id')
                                    <span class="invalid-feedback d-block">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- Estado -->
                            <div class="col-md-6 form-group">
                                <label for="estado">Estado <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-info-circle"></i></span>
                                    </div>
                                    <select name="estado" id="estado" class="form-control @error('estado') is-invalid @enderror" required>
                                        <option value="Disponible" {{ old('estado', $habitacion->estado) == 'Disponible' ? 'selected' : '' }}>Disponible</option>
                                        <option value="Ocupada" {{ old('estado', $habitacion->estado) == 'Ocupada' ? 'selected' : '' }}>Ocupada</option>
                                        <option value="Limpieza" {{ old('estado', $habitacion->estado) == 'Limpieza' ? 'selected' : '' }}>Limpieza</option>
                                        <option value="Mantenimiento" {{ old('estado', $habitacion->estado) == 'Mantenimiento' ? 'selected' : '' }}>Mantenimiento</option>
                                    </select>
                                </div>
                                @error('estado')
                                    <span class="invalid-feedback d-block">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="card-footer d-flex justify-content-between">
                        <a href="{{ route('habitaciones.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left mr-1"></i> Cancelar
                        </a>
                        <button type="submit" class="btn btn-warning">
                            <i class="fas fa-sync-alt mr-1"></i> Actualizar
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection