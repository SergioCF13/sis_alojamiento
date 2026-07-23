@extends('adminlte::page')

@section('title', 'Pagos')

@section('plugins.Datatables', true)
@section('plugins.DatatablesPlugin', true)
@section('plugins.Sweetalert2', true)

@section('content_header')
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Gestión de Pagos</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Inicio</a></li>
                    <li class="breadcrumb-item active">Pagos</li>
                </ol>
            </div>
        </div>
    </div>
@endsection

@section('content')
<div class="container-fluid">
    <div class="card card-success card-outline">
        <div class="card-header">
            <h3 class="card-title mt-1"><i class="fas fa-money-bill-wave mr-1"></i> Listado de pagos</h3>
            <div class="card-tools">
                <a href="{{ route('pagos.create') }}" class="btn btn-success btn-sm">
                    <i class="fas fa-plus mr-1"></i> Nuevo pago
                </a>
            </div>
        </div>
        <div class="card-body">
            <table id="tabla-pagos" class="table table-striped table-bordered table-hover w-100">
                <thead class="thead-light">
                    <tr>
                        <th>Cliente</th>
                        <th>Reserva</th>
                        <th>Monto</th>
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
        tabla = $('#tabla-pagos').DataTable({
            processing: true,
            serverSide: true,
            ajax: '{{ route('pagos.index') }}',
            columns: [
                { data: 'cliente', name: 'cliente' },
                { data: 'reserva', name: 'reserva' },
                { data: 'monto', name: 'monto' },
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

    function confirmarEliminar(id, nombre) {
        Swal.fire({
            title: '¿Eliminar pago?',
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
                    url: `/pagos/${id}`,
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
                        Swal.fire('Error', 'No se pudo eliminar el pago.', 'error');
                    }
                });
            }
        });
    }
</script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@endsection
