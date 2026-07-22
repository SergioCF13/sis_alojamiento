@extends('adminlte::page')

@section('title', 'Nueva Habitación')

@section('content_header')
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Registrar Habitación</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Inicio</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('habitaciones.index') }}">Habitaciones</a></li>
                    <li class="breadcrumb-item active">Nueva</li>
                </ol>
            </div>
        </div>
    </div>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card card-navy card-outline">
                <div class="card-header">
                    <h3 class="card-title"><i class="fas fa-plus-circle mr-1"></i> Formulario de Registro</h3>
                </div>

                <form action="{{ route('habitaciones.store') }}" method="POST">
                    @csrf
                    <div class="card-body">
                        <div class="row">
                            <!-- Número -->
                            <div class="col-md-6 form-group">
                                <label for="numero">Número de Habitación <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-hashtag"></i></span>
                                    </div>
                                    <input type="text" name="numero" id="numero" class="form-control @error('numero') is-invalid @enderror" value="{{ old('numero') }}" placeholder="Ej. 101, 202-A" required>
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
                                    <input type="number" min="1" name="piso" id="piso" class="form-control @error('piso') is-invalid @enderror" value="{{ old('piso', 1) }}" required>
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
                                            <option value="{{ $tipo->id }}" {{ old('tipo_habitacion_id') == $tipo->id ? 'selected' : '' }}>
                                                {{ $tipo->nombre }} (Bs {{ number_format($tipo->precio, 2) }})
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                @error('tipo_habitacion_id')
                                    <span class="invalid-feedback d-block">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- Estado Inicial -->
                            <div class="col-md-6 form-group">
                                <label for="estado">Estado Inicial <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-info-circle"></i></span>
                                    </div>
                                    <select name="estado" id="estado" class="form-control @error('estado') is-invalid @enderror" required>
                                        <option value="Disponible" {{ old('estado', 'Disponible') == 'Disponible' ? 'selected' : '' }}>Disponible</option>
                                        <option value="Ocupada" {{ old('estado') == 'Ocupada' ? 'selected' : '' }}>Ocupada</option>
                                        <option value="Limpieza" {{ old('estado') == 'Limpieza' ? 'selected' : '' }}>Limpieza</option>
                                        <option value="Mantenimiento" {{ old('estado') == 'Mantenimiento' ? 'selected' : '' }}>Mantenimiento</option>
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
                        <button type="submit" class="btn btn-success">
                            <i class="fas fa-save mr-1"></i> Guardar Registro
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection