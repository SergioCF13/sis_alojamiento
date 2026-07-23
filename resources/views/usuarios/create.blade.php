@extends('adminlte::page')

@section('title', 'Crear Usuario')

@section('content_header')
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Crear Usuario</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Inicio</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('usuarios.index') }}">Usuarios</a></li>
                    <li class="breadcrumb-item active">Crear</li>
                </ol>
            </div>
        </div>
    </div>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card card-success card-outline">
                <div class="card-header">
                    <h3 class="card-title"><i class="fas fa-user-plus mr-1"></i> Nuevo usuario</h3>
                </div>
                <form action="{{ route('usuarios.store') }}" method="POST">
                    @csrf
                    <div class="card-body">
                        <div class="form-group">
                            <label for="name">Nombre</label>
                            <input id="name" type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" required>
                            @error('name')<span class="invalid-feedback d-block">{{ $message }}</span>@enderror
                        </div>

                        <div class="form-group">
                            <label for="email">Correo</label>
                            <input id="email" type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}" required>
                            @error('email')<span class="invalid-feedback d-block">{{ $message }}</span>@enderror
                        </div>

                        <div class="form-group">
                            <label for="role">Rol</label>
                            <select id="role" name="role" class="form-control @error('role') is-invalid @enderror" required>
                                <option value="Administrador" {{ old('role') == 'Administrador' ? 'selected' : '' }}>Administrador</option>
                                <option value="Recepcionista" {{ old('role') == 'Recepcionista' ? 'selected' : '' }}>Recepcionista</option>
                                <option value="Limpieza" {{ old('role') == 'Limpieza' ? 'selected' : '' }}>Limpieza</option>
                            </select>
                            @error('role')<span class="invalid-feedback d-block">{{ $message }}</span>@enderror
                        </div>

                        <div class="form-group">
                            <label for="password">Contraseña</label>
                            <input id="password" type="password" name="password" class="form-control @error('password') is-invalid @enderror" required>
                            @error('password')<span class="invalid-feedback d-block">{{ $message }}</span>@enderror
                        </div>

                        <div class="form-group">
                            <label for="password_confirmation">Confirmar contraseña</label>
                            <input id="password_confirmation" type="password" name="password_confirmation" class="form-control" required>
                        </div>
                    </div>

                    <div class="card-footer d-flex justify-content-between">
                        <a href="{{ route('usuarios.index') }}" class="btn btn-secondary"><i class="fas fa-arrow-left mr-1"></i>Cancelar</a>
                        <button type="submit" class="btn btn-success"><i class="fas fa-save mr-1"></i>Guardar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
