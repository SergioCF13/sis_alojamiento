@extends('adminlte::page')

@section('title', 'Nueva Reserva')

@section('content_header')
    <div class="container-fluid">
        <div class="row mb-2 align-items-center">
            <div class="col-sm-6">
                <h1 class="m-0 font-weight-bold text-dark" style="letter-spacing: -0.5px;">
                    <i class="fas fa-concierge-bell text-warning mr-2"></i>Registrar Reserva / Check-in
                </h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right bg-transparent p-0 m-0">
                    <li class="breadcrumb-item"><a href="{{ route('reservas.index') }}" class="text-secondary">Reservas</a></li>
                    <li class="breadcrumb-item active text-dark font-weight-bold">Nueva</li>
                </ol>
            </div>
        </div>
    </div>
@endsection

@section('content')
<div class="container-fluid">

    <!-- PASO 1: CATÁLOGO DE HABITACIONES ELEGANTE -->
    <div id="seccion-catalogo">
        <!-- Banner de Encabezado -->
        <div class="d-flex justify-content-between align-items-center mb-4 p-3 bg-white rounded-lg shadow-sm border-left border-warning" style="border-left-width: 5px !important;">
            <div>
                <h5 class="mb-0 text-dark font-weight-bold">Seleccione la habitación deseada</h5>
                <small class="text-muted">Elija una habitación disponible para asociarla automáticamente al formulario de reserva.</small>
            </div>
            <button type="button" class="btn btn-outline-dark btn-sm rounded-pill px-3 shadow-none" onclick="omitirSeleccion()">
                Sin asignar habitación <i class="fas fa-arrow-right ml-1"></i>
            </button>
        </div>

        <!-- Grid de Tarjetas -->
        <div class="row">
            @forelse($habitaciones as $habitacion)
                <div class="col-12 col-sm-6 col-lg-4 d-flex align-items-stretch mb-4">
                    <div class="card card-habitacion border-0 shadow-sm rounded-xl w-100 position-relative overflow-hidden transition-all">
                        
                        <!-- Header de la Tarjeta con Degradado -->
                        <div class="card-header border-0 bg-navy text-white p-3 d-flex justify-content-between align-items-center">
                            <div class="d-flex align-items-center">
                                <div class="icon-shape bg-white text-navy rounded-circle mr-2 d-flex align-items-center justify-content-center" style="width: 38px; height: 38px;">
                                    <i class="fas fa-bed"></i>
                                </div>
                                <h5 class="m-0 font-weight-bold">Hab. {{ $habitacion->numero }}</h5>
                            </div>

                            @php
                                $badgeColor = 'badge-success';
                                if($habitacion->estado == 'Ocupado') $badgeColor = 'badge-danger';
                                elseif($habitacion->estado == 'Mantenimiento') $badgeColor = 'badge-warning';
                            @endphp
                            <span class="badge {{ $badgeColor }} badge-pill px-3 py-1 text-uppercase font-weight-bold" style="font-size: 0.75rem;">
                                {{ $habitacion->estado }}
                            </span>
                        </div>

                                              <!-- Cuerpo de la Card -->
                        <div class="card-body pt-2 d-flex flex-column">
                            <p class="text-muted small flex-grow-1">
                                <strong>Tipo:</strong> {{ $habitacion->tipo->nombre ?? 'Estándar' }}<br>
                                <strong>Detalle:</strong> {{ Str::limit($habitacion->descripcion ?? $habitacion->tipo->descripcion ?? 'Sin información adicional', 90) }}
                            </p>

                            @if(isset($habitacion->precio) || isset($habitacion->tipo->precio))
                                <div class="text-right my-2">
                                    <span class="h5 font-weight-bold text-success">
                                        Bs {{ number_format($habitacion->precio ?? $habitacion->tipo->precio, 2) }}
                                    </span>
                                    <small class="text-muted">/ noche</small>
                                </div>
                            @endif

                            <hr class="my-2">

                            <!-- <div class="d-flex justify-content-around text-muted small">
                                <span><i class="fas fa-wifi text-info"></i> Wifi</span>
                                <span><i class="fas fa-tv text-secondary"></i> TV</span>
                                <span><i class="fas fa-shower text-primary"></i> Baño</span>
                            </div> -->
                        </div>

                        <!-- Footer / Acciones -->
                        <div class="card-footer bg-white border-0 p-3 pt-0">
                            @if($habitacion->estado == 'Ocupado')
                                <button type="button" class="btn btn-secondary btn-block rounded-lg py-2 font-weight-bold" disabled>
                                    <i class="fas fa-lock mr-1"></i> No Disponible
                                </button>
                            @else
                                <button type="button" 
                                        class="btn btn-navy btn-block rounded-lg py-2 font-weight-bold shadow-sm"
                                        onclick="seleccionarHabitacion('{{ $habitacion->id }}', '{{ $habitacion->numero }}')">
                                    <i class="fas fa-calendar-check mr-1 text-warning"></i> Reservar esta habitación
                                </button>
                            @endif
                        </div>

                    </div>
                </div>
            @empty
                <div class="col-12">
                    <div class="card border-0 shadow-sm p-5 text-center rounded-xl bg-white">
                        <i class="fas fa-hotel fa-3x text-muted mb-3"></i>
                        <h4 class="text-muted">No hay habitaciones registradas</h4>
                        <p class="text-muted mb-0">Por favor, registre habitaciones primero en el panel correspondiente.</p>
                    </div>
                </div>
            @endforelse
        </div>
    </div>


    <!-- PASO 2: FORMULARIO DE RESERVA -->
    <div id="seccion-formulario" style="display: none;">
        <div class="row justify-content-center">
            <div class="col-md-10">

                <!-- Alerta Superior Estilizada -->
                <div class="card border-0 shadow-sm rounded-xl mb-4 bg-navy text-white overflow-hidden">
                    <div class="card-body p-3 d-flex justify-content-between align-items-center">
                        <div class="d-flex align-items-center">
                            <div class="icon-shape bg-warning text-dark rounded-circle mr-3 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                <i class="fas fa-check fa-lg"></i>
                            </div>
                            <div>
                                <small class="text-uppercase text-light font-weight-bold">Selección Activa</small>
                                <h6 class="m-0 font-weight-bold" id="texto-habitacion-seleccionada">--</h6>
                            </div>
                        </div>
                        <button type="button" class="btn btn-outline-light btn-sm rounded-pill px-3" onclick="volverACatalogo()">
                            <i class="fas fa-exchange-alt mr-1"></i> Cambiar Habitación
                        </button>
                    </div>
                </div>

                <div class="card border-0 shadow-lg rounded-xl overflow-hidden">
                    <div class="card-header bg-white border-bottom p-4">
                        <h4 class="card-title font-weight-bold text-dark m-0">
                            <i class="fas fa-id-card text-navy mr-2"></i>Completar Información de la Reserva
                        </h4>
                    </div>

                    <form action="{{ route('reservas.store') }}" method="POST">
                        @csrf
                        <div class="card-body p-4 bg-white">
                            
                            <!-- Sección 1: Huésped y Asignación -->
                            <div class="form-row mb-3">
                                <div class="col-md-6 form-group">
                                    <label for="cliente_id" class="font-weight-bold text-secondary">Cliente / Huésped <span class="text-danger">*</span></label>
                                    <select name="cliente_id" id="cliente_id" class="form-control custom-select rounded-lg @error('cliente_id') is-invalid @enderror" required>
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
                                    <label for="habitacion_id" class="font-weight-bold text-secondary">Habitación Asignada</label>
                                    <select name="habitacion_id" id="habitacion_id" class="form-control custom-select rounded-lg @error('habitacion_id') is-invalid @enderror">
                                        <option value="">-- Sin habitación asignada (check-in directo) --</option>
                                        @foreach($habitaciones as $habitacion)
                                            <option value="{{ $habitacion->id }}" {{ old('habitacion_id') == $habitacion->id ? 'selected' : '' }}>
                                                Habitación {{ $habitacion->numero }} ({{ $habitacion->estado }})
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('habitacion_id')<span class="invalid-feedback d-block">{{ $message }}</span>@enderror
                                </div>
                            </div>

                            <!-- Sección 2: Estancia -->
                            <div class="p-3 bg-light rounded-lg mb-4">
                                <h6 class="font-weight-bold text-uppercase text-muted mb-3" style="font-size: 0.8rem; letter-spacing: 1px;">Fechas y Horarios de Estancia</h6>
                                <div class="form-row">
                                    <div class="col-md-3 form-group">
                                        <label for="fecha_ingreso" class="small font-weight-bold">Fecha Ingreso <span class="text-danger">*</span></label>
                                        <input type="date" name="fecha_ingreso" id="fecha_ingreso" class="form-control rounded-lg @error('fecha_ingreso') is-invalid @enderror" value="{{ old('fecha_ingreso', now()->toDateString()) }}" required>
                                        @error('fecha_ingreso')<span class="invalid-feedback d-block">{{ $message }}</span>@enderror
                                    </div>

                                    <div class="col-md-3 form-group">
                                        <label for="hora_ingreso" class="small font-weight-bold">Hora Ingreso <span class="text-danger">*</span></label>
                                        <input type="time" name="hora_ingreso" id="hora_ingreso" class="form-control rounded-lg @error('hora_ingreso') is-invalid @enderror" value="{{ old('hora_ingreso', '14:00') }}" required>
                                        @error('hora_ingreso')<span class="invalid-feedback d-block">{{ $message }}</span>@enderror
                                    </div>

                                    <div class="col-md-3 form-group">
                                        <label for="fecha_salida" class="small font-weight-bold">Fecha Salida <span class="text-danger">*</span></label>
                                        <input type="date" name="fecha_salida" id="fecha_salida" class="form-control rounded-lg @error('fecha_salida') is-invalid @enderror" value="{{ old('fecha_salida', now()->addDay()->toDateString()) }}" required>
                                        @error('fecha_salida')<span class="invalid-feedback d-block">{{ $message }}</span>@enderror
                                    </div>

                                    <div class="col-md-3 form-group">
                                        <label for="hora_salida" class="small font-weight-bold">Hora Salida <span class="text-danger">*</span></label>
                                        <input type="time" name="hora_salida" id="hora_salida" class="form-control rounded-lg @error('hora_salida') is-invalid @enderror" value="{{ old('hora_salida', '12:00') }}" required>
                                        @error('hora_salida')<span class="invalid-feedback d-block">{{ $message }}</span>@enderror
                                    </div>
                                </div>
                            </div>

                            <!-- Sección 3: Parámetros Finales -->
                            <div class="form-row mb-3">
                                <div class="col-md-6 form-group">
                                    <label for="cantidad_persona" class="font-weight-bold text-secondary">Cantidad de Personas <span class="text-danger">*</span></label>
                                    <input type="number" min="1" name="cantidad_persona" id="cantidad_persona" class="form-control rounded-lg @error('cantidad_persona') is-invalid @enderror" value="{{ old('cantidad_persona', 1) }}" required>
                                    @error('cantidad_persona')<span class="invalid-feedback d-block">{{ $message }}</span>@enderror
                                </div>

                                <div class="col-md-6 form-group">
                                    <label for="estado" class="font-weight-bold text-secondary">Estado Inicial <span class="text-danger">*</span></label>
                                    <select name="estado" id="estado" class="form-control custom-select rounded-lg @error('estado') is-invalid @enderror" required>
                                        <option value="Reserva" {{ old('estado', 'Reserva') == 'Reserva' ? 'selected' : '' }}>Reserva</option>
                                        <option value="Check-in" {{ old('estado') == 'Check-in' ? 'selected' : '' }}>Check-in</option>
                                        <option value="Check-out" {{ old('estado') == 'Check-out' ? 'selected' : '' }}>Check-out</option>
                                        <option value="Cancelada" {{ old('estado') == 'Cancelada' ? 'selected' : '' }}>Cancelada</option>
                                    </select>
                                    @error('estado')<span class="invalid-feedback d-block">{{ $message }}</span>@enderror
                                </div>
                            </div>

                            <div class="form-group mb-0">
                                <label for="observaciones" class="font-weight-bold text-secondary">Observaciones Adicionales</label>
                                <textarea name="observaciones" id="observaciones" rows="3" class="form-control rounded-lg @error('observaciones') is-invalid @enderror" placeholder="Preferencias del huésped, requerimientos especiales, etc.">{{ old('observaciones') }}</textarea>
                                @error('observaciones')<span class="invalid-feedback d-block">{{ $message }}</span>@enderror
                            </div>

                        </div>

                        <div class="card-footer bg-light p-4 d-flex justify-content-between align-items-center">
                            <button type="button" class="btn btn-outline-secondary rounded-pill px-4 font-weight-bold" onclick="volverACatalogo()">
                                <i class="fas fa-arrow-left mr-1"></i> Volver a Selección
                            </button>
                            <button type="submit" class="btn btn-success rounded-pill px-5 py-2 font-weight-bold shadow-sm">
                                <i class="fas fa-check-circle mr-1"></i> Guardar y Confirmar
                            </button>
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </div>

</div>
@endsection

@section('css')
<style>
    /* Estilos personalizados */
    .btn-navy {
        background-color: #1a252f;
        color: #ffffff;
        border: none;
    }
    .btn-navy:hover {
        background-color: #2c3e50;
        color: #ffffff;
    }
    .bg-navy {
        background-color: #1a252f !important;
    }
    .text-navy {
        color: #1a252f !important;
    }
    .rounded-xl {
        border-radius: 1rem !important;
    }
    .transition-all {
        transition: all 0.3s ease-in-out;
    }
    .card-habitacion:hover {
        transform: translateY(-5px);
        box-shadow: 0 1rem 2rem rgba(0,0,0,.12) !important;
    }
</style>
@endsection

@section('js')
<script>
    function seleccionarHabitacion(id, numero) {
        $('#habitacion_id').val(id);
        $('#texto-habitacion-seleccionada').text('Habitación N° ' + numero);

        $('#seccion-catalogo').fadeOut(250, function() {
            $('#seccion-formulario').fadeIn(250);
        });
    }

    function volverACatalogo() {
        $('#seccion-formulario').fadeOut(250, function() {
            $('#seccion-catalogo').fadeIn(250);
        });
    }

    function omitirSeleccion() {
        $('#habitacion_id').val('');
        $('#texto-habitacion-seleccionada').text('Sin habitación asignada');
        
        $('#seccion-catalogo').fadeOut(250, function() {
            $('#seccion-formulario').fadeIn(250);
        });
    }

    // Si ocurre un error de validación tras enviar, mantiene visible la vista del formulario
    @if ($errors->any())
        $(document).ready(function() {
            $('#seccion-catalogo').hide();
            $('#seccion-formulario').show();
        });
    @endif
</script>
@endsection