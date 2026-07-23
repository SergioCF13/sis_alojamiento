@extends('adminlte::auth.auth-page', ['auth_type' => 'register'])

@section('auth_body')
    <form method="post" action="{{ route('register') }}">
        @csrf

        <div class="form-group">
            <label for="name">Nombre</label>
            <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required autocomplete="name" autofocus>
            @error('name')<span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>@enderror
        </div>

        <div class="form-group">
            <label for="email">Correo</label>
            <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email">
            @error('email')<span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>@enderror
        </div>

        <div class="form-group">
            <label for="role">Rol</label>
            <select id="role" name="role" class="form-control @error('role') is-invalid @enderror" required>
                <option value="Administrador" {{ old('role') == 'Administrador' ? 'selected' : '' }}>Administrador</option>
                <option value="Recepcionista" {{ old('role') == 'Recepcionista' ? 'selected' : '' }}>Recepcionista</option>
                <option value="Limpieza" {{ old('role') == 'Limpieza' ? 'selected' : '' }}>Limpieza</option>
            </select>
            @error('role')<span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>@enderror
        </div>

        <div class="form-group">
            <label for="password">Contraseña</label>
            <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password">
            @error('password')<span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>@enderror
        </div>

        <div class="form-group">
            <label for="password-confirm">Confirmar contraseña</label>
            <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required autocomplete="new-password">
        </div>

        <button type="submit" class="btn btn-primary btn-block">Registrar</button>
    </form>
@endsection