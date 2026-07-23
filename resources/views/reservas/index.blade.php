@extends('adminlte::page')

@section('title', 'Reservas')

@section('plugins.Datatables', true)
@section('plugins.DatatablesPlugin', true)
@section('plugins.Sweetalert2', true)

@section('content_header')
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Gestión de Reservas</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Inicio</a></li>
                    <li class="breadcrumb-item active">Reservas</li>
                </ol>
            </div>
        </div>
    </div>
@endsection

@section('content')
<div class="container-fluid">
    <div class="card card-primary card-outline">
        <div class="card-header">
            <h3 class="card-title mt-1"><i class="fas fa-calendar-check mr-1"></i> Listado de reservas</h3>
            <div class="card-tools">
                <a href="{{ route('reservas.create') }}" class="btn btn-primary btn-sm">
                    <i class="fas fa-plus mr-1"></i> Nueva reserva
                </a>
            </div>
        </div>
        <div class="card-body">
            <table id="tabla-reservas" class="table table-striped table-bordered table-hover w-100">
                <thead class="thead-light">
                    <tr>
                        <th>Cliente</th>
                        <th>Habitación</th>
                        <th>Ingreso / Salida</th>
                        <th>Estado</th>
                        <th class="text-right" style="width: 140px;">Acciones</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@section('js')
<script>
    let tabla;

    $(document).ready(function () {
        tabla = $('#tabla-reservas').DataTable({
            processing: true,
            serverSide: true,
            ajax: '{{ route('reservas.index') }}',
            columns: [
                { data: 'cliente', name: 'cliente' },
                { data: 'habitacion', name: 'habitacion' },
                { data: 'fecha', name: 'fecha' },
                { data: 'estado', name: 'estado' },
                { data: 'acciones', name: 'acciones', orderable: false, searchable: false, className: 'text-right align-middle' }
            ],
            responsive: true,
            autoWidth: false,
            language: {
                sProcessing: 'Procesando...',
                sLengthMenu: 'Mostrar _MENU_ registros',
                sZeroRecords: 'No se encontraron resultados',
                sEmptyTable: 'Ningún dato disponible en esta tabla',
                sInfo: 'Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros',
                sInfoEmpty: 'Mostrando registros del 0 al 0 de un total de 0 registros',
                sInfoFiltered: '(filtrado de un total de _MAX_ registros)',
                sSearch: 'Buscar:',
                oPaginate: {
                    sFirst: 'Primero',
                    sLast: 'Último',
                    sNext: 'Siguiente',
                    sPrevious: 'Anterior'
                }
            }
        });

        @if(session('success'))
            Swal.fire({
                title: '¡Éxito!',
                text: '{{ session('success') }}',
                icon: 'success',
                timer: 2200,
                showConfirmButton: false
            });
        @endif
    });

    function cambiarEstado(id, estado) {
        const title = estado === 'Check-in' ? '¿Registrar check-in?' : '¿Registrar check-out?';
        const text = estado === 'Check-in'
            ? 'Se cambiará el estado de la reserva a Check-in.'
            : 'Se cambiará el estado de la reserva a Check-out.';

        Swal.fire({
            title: title,
            text: text,
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#28a745',
            cancelButtonColor: '#6c757d',
            confirmButtonText: estado === 'Check-in' ? '<i class="fas fa-sign-in-alt mr-1"></i> Sí, hacer check-in' : '<i class="fas fa-sign-out-alt mr-1"></i> Sí, hacer check-out',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.value || result.isConfirmed) {
                $.ajax({
                    url: `/reservas/${id}/cambiar-estado`,
                    type: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        estado: estado
                    },
                    success: function (response) {
                        tabla.ajax.reload(null, false);
                        Swal.fire({
                            title: '¡Listo!',
                            text: response.message,
                            icon: 'success',
                            timer: 2000,
                            showConfirmButton: false
                        });
                    },
                    error: function () {
                        Swal.fire('Error', 'No se pudo cambiar el estado de la reserva.', 'error');
                    }
                });
            }
        });
    }

    function confirmarEliminar(id, nombre) {
        Swal.fire({
            title: '¿Eliminar reserva?',
            html: `¿Estás seguro de eliminar <strong>${nombre}</strong>?`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#6c757d',
            confirmButtonText: '<i class="fas fa-trash mr-1"></i> Sí, eliminar',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.value || result.isConfirmed) {
                $.ajax({
                    url: `/reservas/${id}`,
                    type: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        _method: 'DELETE'
                    },
                    success: function (response) {
                        tabla.ajax.reload(null, false);
                        Swal.fire({
                            title: '¡Eliminado!',
                            text: response.message,
                            icon: 'success',
                            timer: 2000,
                            showConfirmButton: false
                        });
                    },
                    error: function () {
                        Swal.fire('Error', 'No se pudo eliminar la reserva.', 'error');
                    }
                });
            }
        });
    }
</script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@endsection
