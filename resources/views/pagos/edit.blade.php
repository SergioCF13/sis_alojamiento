@extends('adminlte::page')

@section('title', 'Editar Pago')

@section('content_header')
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Editar Pago</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('pagos.index') }}">Pagos</a></li>
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
                    <h3 class="card-title"><i class="fas fa-edit mr-1"></i> Modificar datos</h3>
                </div>
                <form action="{{ route('pagos.update', $pago) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="card-body">
                        <div class="form-group">
                            <label for="reserva_id">Reserva <span class="text-danger">*</span></label>
                            <select name="reserva_id" id="reserva_id" class="form-control @error('reserva_id') is-invalid @enderror" required>
                                <option value="">-- Seleccionar reserva --</option>
                                @foreach($reservas as $reserva)
                                    <option value="{{ $reserva->id }}" {{ old('reserva_id', $pago->reserva_id) == $reserva->id ? 'selected' : '' }}>
                                        Reserva #{{ $reserva->id }} - {{ $reserva->cliente?->nombre_completo ?? 'Sin cliente' }}
                                    </option>
                                @endforeach
                            </select>
                            @error('reserva_id')<span class="invalid-feedback d-block">{{ $message }}</span>@enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6 form-group">
                                <label for="monto">Monto <span class="text-danger">*</span></label>
                                <input type="number" step="0.01" min="0" name="monto" id="monto" class="form-control @error('monto') is-invalid @enderror" value="{{ old('monto', $pago->monto) }}" required>
                                @error('monto')<span class="invalid-feedback d-block">{{ $message }}</span>@enderror
                            </div>
                            <div class="col-md-6 form-group">
                                <label for="metodo_pago">Método de pago <span class="text-danger">*</span></label>
                                <select name="metodo_pago" id="metodo_pago" class="form-control @error('metodo_pago') is-invalid @enderror" required>
                                    <option value="Efectivo" {{ old('metodo_pago', $pago->metodo_pago) == 'Efectivo' ? 'selected' : '' }}>Efectivo</option>
                                    <option value="Transferencia" {{ old('metodo_pago', $pago->metodo_pago) == 'Transferencia' ? 'selected' : '' }}>Transferencia</option>
                                    <option value="QR" {{ old('metodo_pago', $pago->metodo_pago) == 'QR' ? 'selected' : '' }}>QR</option>
                                    <option value="Tarjeta" {{ old('metodo_pago', $pago->metodo_pago) == 'Tarjeta' ? 'selected' : '' }}>Tarjeta</option>
                                </select>
                                @error('metodo_pago')<span class="invalid-feedback d-block">{{ $message }}</span>@enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 form-group">
                                <label for="estado">Estado <span class="text-danger">*</span></label>
                                <select name="estado" id="estado" class="form-control @error('estado') is-invalid @enderror" required>
                                    <option value="Pagado" {{ old('estado', $pago->estado) == 'Pagado' ? 'selected' : '' }}>Pagado</option>
                                    <option value="Pendiente" {{ old('estado', $pago->estado) == 'Pendiente' ? 'selected' : '' }}>Pendiente</option>
                                </select>
                                @error('estado')<span class="invalid-feedback d-block">{{ $message }}</span>@enderror
                            </div>
                            <div class="col-md-6 form-group">
                                <label for="fecha_pago">Fecha de pago <span class="text-danger">*</span></label>
                                <input type="date" name="fecha_pago" id="fecha_pago" class="form-control @error('fecha_pago') is-invalid @enderror" value="{{ old('fecha_pago', $pago->fecha_pago) }}" required>
                                @error('fecha_pago')<span class="invalid-feedback d-block">{{ $message }}</span>@enderror
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="observaciones">Observaciones</label>
                            <textarea name="observaciones" id="observaciones" rows="3" class="form-control @error('observaciones') is-invalid @enderror">{{ old('observaciones', $pago->observaciones) }}</textarea>
                            @error('observaciones')<span class="invalid-feedback d-block">{{ $message }}</span>@enderror
                        </div>
                    </div>

                    <div class="card-footer d-flex justify-content-between">
                        <a href="{{ route('pagos.index') }}" class="btn btn-secondary"><i class="fas fa-arrow-left mr-1"></i>Cancelar</a>
                        <button type="submit" class="btn btn-warning"><i class="fas fa-save mr-1"></i>Actualizar pago</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
