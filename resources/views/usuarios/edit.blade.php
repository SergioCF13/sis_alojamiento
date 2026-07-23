@extends('adminlte::page')

@section('title', 'Editar Usuario')

@section('content_header')
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Editar Usuario</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Inicio</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('usuarios.index') }}">Usuarios</a></li>
                    <li class="breadcrumb-item active">Editar</li>
                </ol>
            </div>
        </div>
    </div>
@endsection

@section('content')
<div class="container-fluid">
    <div class="card card-warning card-outline">
        <div class="card-header">
            <h3 class="card-title"><i class="fas fa-user-edit mr-1"></i> Editar usuario</h3>
        </div>
        <div class="card-body">
            <form action="{{ route('usuarios.update', $usuario) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="form-group">
                    <label for="name">Nombre</label>
                    <input type="text" name="name" id="name" class="form-control" value="{{ old('name', $usuario->name) }}" required>
                </div>

                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" name="email" id="email" class="form-control" value="{{ old('email', $usuario->email) }}" required>
                </div>

                <div class="form-group">
                    <label for="password">Nueva contraseña <small class="text-muted">(opcional)</small></label>
                    <input type="password" name="password" id="password" class="form-control">
                </div>

                <div class="form-group">
                    <label for="password_confirmation">Confirmar contraseña</label>
                    <input type="password" name="password_confirmation" id="password_confirmation" class="form-control">
                </div>

                <div class="form-group">
                    <label for="role">Rol</label>
                    <select name="role" id="role" class="form-control" required>
                        <option value="Administrador" {{ old('role', $usuario->role) === 'Administrador' ? 'selected' : '' }}>Administrador</option>
                        <option value="Recepcionista" {{ old('role', $usuario->role) === 'Recepcionista' ? 'selected' : '' }}>Recepcionista</option>
                        <option value="Limpieza" {{ old('role', $usuario->role) === 'Limpieza' ? 'selected' : '' }}>Limpieza</option>
                    </select>
                </div>

                <button type="submit" class="btn btn-warning">Actualizar usuario</button>
                <a href="{{ route('usuarios.index') }}" class="btn btn-secondary">Cancelar</a>
            </form>
        </div>
    </div>
</div>
@endsection
