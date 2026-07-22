@extends('adminlte::page')

@section('title', 'Detalle del Cliente')

@section('content_header')
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Detalle del Cliente</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Inicio</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('clientes.index') }}">Clientes</a></li>
                    <li class="breadcrumb-item active">Ver Detalle</li>
                </ol>
            </div>
        </div>
    </div>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card card-info card-outline">
                <div class="card-header">
                    <h3 class="card-title mt-1">
                        <i class="fas fa-id-card mr-1"></i> Información del Cliente
                    </h3>
                    <div class="card-tools">
                        <a href="{{ route('clientes.index') }}" class="btn btn-secondary btn-sm">
                            <i class="fas fa-arrow-left mr-1"></i> Volver a la lista
                        </a>
                    </div>
                </div>

                <div class="card-body">
                    <div class="row">
                        {{-- Icono de Perfil / Avatar dinámico --}}
                        <div class="col-md-3 text-center d-flex align-items-center justify-content-center mb-3 mb-md-0">
                            <div class="bg-light p-4 rounded-circle border">
                                <i class="fas fa-user-circle fa-5x text-info"></i>
                            </div>
                        </div>

                        {{-- Datos del Cliente --}}
                        <div class="col-md-9">
                            <dl class="row mb-0">
                                <dt class="col-sm-4 text-muted">
                                    <i class="fas fa-user mr-1"></i> Nombre completo:
                                </dt>
                                <dd class="col-sm-8 font-weight-bold">
                                    {{ $cliente->nombre_completo }}
                                </dd>

                                <dt class="col-sm-4 text-muted">
                                    <i class="fas fa-id-card mr-1"></i> Carnet de identidad:
                                </dt>
                                <dd class="col-sm-8">
                                    <span class="badge badge-light border px-2 py-1">
                                        {{ $cliente->carnet_identidad }}
                                    </span>
                                </dd>

                                <dt class="col-sm-4 text-muted">
                                    <i class="fas fa-phone-alt mr-1"></i> Celular:
                                </dt>
                                <dd class="col-sm-8">
                                    @if($cliente->celular)
                                        <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $cliente->celular) }}" 
                                           target="_blank" 
                                           class="text-success font-weight-bold" 
                                           title="Abrir en WhatsApp">
                                            <i class="fab fa-whatsapp mr-1"></i> {{ $cliente->celular }}
                                        </a>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </dd>

                                {{-- Opcional: Fechas de auditoría --}}
                                @if(isset($cliente->created_at))
                                    <dt class="col-sm-4 text-muted">
                                        <i class="fas fa-calendar-alt mr-1"></i> Registrado el:
                                    </dt>
                                    <dd class="col-sm-8 text-sm text-secondary">
                                        {{ $cliente->created_at->format('d/m/Y H:i') }}
                                    </dd>
                                @endif
                            </dl>
                        </div>
                    </div>
                </div>

                {{-- Acciones rápidas en el Footer --}}
                <div class="card-footer d-flex justify-content-between align-items-center">
                    <a href="{{ route('clientes.index') }}" class="btn btn-default">
                        <i class="fas fa-arrow-left mr-1"></i> Volver
                    </a>

                    <div>
                        <a href="{{ route('clientes.edit', $cliente) }}" class="btn btn-warning mr-1">
                            <i class="fas fa-pencil-alt mr-1"></i> Editar
                        </a>

                        <form action="{{ route('clientes.destroy', $cliente) }}" 
                              method="POST" 
                              class="d-inline-block" 
                              onsubmit="return confirm('¿Seguro que deseas eliminar este cliente?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger">
                                <i class="fas fa-trash mr-1"></i> Eliminar
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection