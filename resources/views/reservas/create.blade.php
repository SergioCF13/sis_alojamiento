@extends('adminlte::page')

@section('title', 'Nueva Reserva')

@section('content_header')
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Registrar Reserva / Check-in</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('reservas.index') }}">Reservas</a></li>
                    <li class="breadcrumb-item active">Nueva</li>
                </ol>
            </div>
        </div>
    </div>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card card-primary card-outline">
                <div class="card-header">
                    <h3 class="card-title"><i class="fas fa-calendar-plus mr-1"></i> Datos de la reserva</h3>
                </div>
                <form action="{{ route('reservas.store') }}" method="POST">
                    @csrf
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 form-group">
                                <label for="cliente_id">Cliente <span class="text-danger">*</span></label>
                                <select name="cliente_id" id="cliente_id" class="form-control @error('cliente_id') is-invalid @enderror" required>
                                    <option value="">-- Seleccionar cliente --</option>
                                    @foreach($clientes as $cliente)
                                        <option value="{{ $cliente->id }}" {{ old('cliente_id') == $cliente->id ? 'selected' : '' }}>
                                            {{ $cliente->nombre_completo }} - {{ $cliente->carnet_identidad }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('cliente_id')<span class="invalid-feedback d-block">{{ $message }}</span>@enderror
                            </div>

                            <div class="col-md-6 form-group">
                                <label for="habitacion_id">Habitación</label>
                                <select name="habitacion_id" id="habitacion_id" class="form-control @error('habitacion_id') is-invalid @enderror">
                                    <option value="">-- Sin habitación asignada (check-in directo) --</option>
                                    @foreach($habitaciones as $habitacion)
                                        <option value="{{ $habitacion->id }}" {{ old('habitacion_id') == $habitacion->id ? 'selected' : '' }}>
                                            {{ $habitacion->numero }} - {{ $habitacion->estado }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('habitacion_id')<span class="invalid-feedback d-block">{{ $message }}</span>@enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-3 form-group">
                                <label for="fecha_ingreso">Fecha ingreso <span class="text-danger">*</span></label>
                                <input type="date" name="fecha_ingreso" id="fecha_ingreso" class="form-control @error('fecha_ingreso') is-invalid @enderror" value="{{ old('fecha_ingreso', now()->toDateString()) }}" required>
                                @error('fecha_ingreso')<span class="invalid-feedback d-block">{{ $message }}</span>@enderror
                            </div>
                            <div class="col-md-3 form-group">
                                <label for="hora_ingreso">Hora ingreso <span class="text-danger">*</span></label>
                                <input type="time" name="hora_ingreso" id="hora_ingreso" class="form-control @error('hora_ingreso') is-invalid @enderror" value="{{ old('hora_ingreso', '14:00') }}" required>
                                @error('hora_ingreso')<span class="invalid-feedback d-block">{{ $message }}</span>@enderror
                            </div>
                            <div class="col-md-3 form-group">
                                <label for="fecha_salida">Fecha salida <span class="text-danger">*</span></label>
                                <input type="date" name="fecha_salida" id="fecha_salida" class="form-control @error('fecha_salida') is-invalid @enderror" value="{{ old('fecha_salida', now()->addDay()->toDateString()) }}" required>
                                @error('fecha_salida')<span class="invalid-feedback d-block">{{ $message }}</span>@enderror
                            </div>
                            <div class="col-md-3 form-group">
                                <label for="hora_salida">Hora salida <span class="text-danger">*</span></label>
                                <input type="time" name="hora_salida" id="hora_salida" class="form-control @error('hora_salida') is-invalid @enderror" value="{{ old('hora_salida', '12:00') }}" required>
                                @error('hora_salida')<span class="invalid-feedback d-block">{{ $message }}</span>@enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4 form-group">
                                <label for="cantidad_persona">Cantidad de personas <span class="text-danger">*</span></label>
                                <input type="number" min="1" name="cantidad_persona" id="cantidad_persona" class="form-control @error('cantidad_persona') is-invalid @enderror" value="{{ old('cantidad_persona', 1) }}" required>
                                @error('cantidad_persona')<span class="invalid-feedback d-block">{{ $message }}</span>@enderror
                            </div>
                            <div class="col-md-4 form-group">
                                <label for="estado">Estado <span class="text-danger">*</span></label>
                                <select name="estado" id="estado" class="form-control @error('estado') is-invalid @enderror" required>
                                    <option value="Reserva" {{ old('estado', 'Reserva') == 'Reserva' ? 'selected' : '' }}>Reserva</option>
                                    <option value="Check-in" {{ old('estado') == 'Check-in' ? 'selected' : '' }}>Check-in</option>
                                    <option value="Check-out" {{ old('estado') == 'Check-out' ? 'selected' : '' }}>Check-out</option>
                                    <option value="Cancelada" {{ old('estado') == 'Cancelada' ? 'selected' : '' }}>Cancelada</option>
                                </select>
                                @error('estado')<span class="invalid-feedback d-block">{{ $message }}</span>@enderror
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="observaciones">Observaciones</label>
                            <textarea name="observaciones" id="observaciones" rows="3" class="form-control @error('observaciones') is-invalid @enderror" placeholder="Ej. Llegada directa, necesita habitación con vista, etc.">{{ old('observaciones') }}</textarea>
                            @error('observaciones')<span class="invalid-feedback d-block">{{ $message }}</span>@enderror
                        </div>
                    </div>

                    <div class="card-footer d-flex justify-content-between">
                        <a href="{{ route('reservas.index') }}" class="btn btn-secondary"><i class="fas fa-arrow-left mr-1"></i>Cancelar</a>
                        <button type="submit" class="btn btn-success"><i class="fas fa-save mr-1"></i>Guardar reserva</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
