@extends('adminlte::page')

@section('title', 'Mi Perfil')

@section('content_header')
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Mi Perfil</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Inicio</a></li>
                    <li class="breadcrumb-item active">Perfil</li>
                </ol>
            </div>
        </div>
    </div>
@endsection

@section('content')
<div class="container-fluid">
    <div class="card card-primary card-outline">
        <div class="card-header">
            <h3 class="card-title"><i class="fas fa-user-circle mr-1"></i> Información del usuario</h3>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <p><strong>Nombre:</strong> {{ $user->name }}</p>
                    <p><strong>Email:</strong> {{ $user->email }}</p>
                    <p><strong>Rol:</strong> <span class="badge badge-info">{{ $user->role }}</span></p>
                </div>
                <div class="col-md-6">
                    <p><strong>Fecha de creación:</strong> {{ $user->created_at ? $user->created_at->format('d/m/Y H:i') : 'N/A' }}</p>
                    <p><strong>Última actualización:</strong> {{ $user->updated_at ? $user->updated_at->format('d/m/Y H:i') : 'N/A' }}</p>
                    <p><strong>Estado:</strong> <span class="badge badge-success">Activo</span></p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
