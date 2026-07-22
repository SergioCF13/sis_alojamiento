@extends('adminlte::page')

@section('title', 'Editar Cliente')

@section('content_header')
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Editar Cliente</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Inicio</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('clientes.index') }}">Clientes</a></li>
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
                    <h3 class="card-title">
                        <i class="fas fa-user-edit mr-1"></i> Modificar Datos del Cliente
                    </h3>
                </div>

                <form action="{{ route('clientes.update', $cliente) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="card-body">
                        <!-- Nombre Completo -->
                        <div class="form-group">
                            <label for="nombre_completo">Nombre completo</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-user"></i></span>
                                </div>
                                <input type="text" 
                                       name="nombre_completo" 
                                       id="nombre_completo" 
                                       class="form-control @error('nombre_completo') is-invalid @enderror" 
                                       value="{{ old('nombre_completo', $cliente->nombre_completo) }}"
                                       placeholder="Ej. Juan Pérez">
                                @error('nombre_completo')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <!-- Carnet de Identidad -->
                        <div class="form-group">
                            <label for="carnet_identidad">Carnet de identidad</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-id-card"></i></span>
                                </div>
                                <input type="text" 
                                       name="carnet_identidad" 
                                       id="carnet_identidad" 
                                       class="form-control @error('carnet_identidad') is-invalid @enderror" 
                                       value="{{ old('carnet_identidad', $cliente->carnet_identidad) }}"
                                       placeholder="Ej. 12345678">
                                @error('carnet_identidad')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <!-- Celular -->
                        <div class="form-group">
                            <label for="celular">Celular</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-phone"></i></span>
                                </div>
                                <input type="text" 
                                       name="celular" 
                                       id="celular" 
                                       class="form-control @error('celular') is-invalid @enderror" 
                                       value="{{ old('celular', $cliente->celular) }}"
                                       placeholder="Ej. 70000000">
                                @error('celular')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Botones de Acción -->
                    <div class="card-footer d-flex justify-content-between">
                        <a href="{{ route('clientes.index') }}" class="btn btn-secondary">
                            <i class="fas fa-times mr-1"></i> Cancelar
                        </a>
                        <button type="submit" class="btn btn-warning">
                            <i class="fas fa-sync-alt mr-1"></i> Actualizar cliente
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection