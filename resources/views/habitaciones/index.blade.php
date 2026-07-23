@extends('adminlte::page')

@section('title', 'Habitaciones')

@section('plugins.Datatables', true)
@section('plugins.DatatablesPlugin', true)
@section('plugins.Sweetalert2', true)

@section('content_header')
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Habitaciones</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Inicio</a></li>
                    <li class="breadcrumb-item active">Habitaciones</li>
                </ol>
            </div>
        </div>
    </div>
@endsection

@section('content')
<div class="container-fluid">
    <div class="card card-navy card-outline">
        <div class="card-header">
            <h3 class="card-title mt-1">
                <i class="fas fa-door-closed mr-1"></i> Listado de Habitaciones
            </h3>
            <div class="card-tools">
                @if(auth()->user() && auth()->user()->role === 'Administrador')
                    <a href="{{ route('habitaciones.create') }}" class="btn btn-navy btn-sm" style="background-color: #001f3f; color: #fff;">
                        <i class="fas fa-plus mr-1"></i> Nueva Habitación
                    </a>
                @endif
            </div>
        </div>

        <div class="card-body">
            <table id="tabla-habitaciones" class="table table-striped table-bordered table-hover w-100">
                <thead class="thead-light">
                    <tr>
                        <th class="text-center">Número</th>
                        <th>Tipo de Habitación</th>
                        <th class="text-center">Piso</th>
                        <th class="text-center">Estado</th>
                        <th class="text-right" style="width: 120px;">Acciones</th>
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

    $(document).ready(function() {
        tabla = $('#tabla-habitaciones').DataTable({
            "processing": true,
            "serverSide": true,
            "ajax": "{{ route('habitaciones.index') }}",
            "columns": [
                { data: 'numero', name: 'numero', className: 'text-center font-weight-bold align-middle' },
                { data: 'tipo_habitacion', name: 'tipoHabitacion.nombre', className: 'align-middle' },
                { data: 'piso', name: 'piso', className: 'text-center align-middle' },
                { data: 'estado', name: 'estado', className: 'text-center align-middle' },
                { data: 'acciones', name: 'acciones', orderable: false, searchable: false, className: 'text-right align-middle' }
            ],
            "responsive": true,
            "autoWidth": false,
            "language": {
                "sProcessing":     "Procesando...",
                "sLengthMenu":     "Mostrar _MENU_ registros",
                "sZeroRecords":    "No se encontraron resultados",
                "sEmptyTable":     "Ningún dato disponible en esta tabla",
                "sInfo":           "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
                "sInfoEmpty":      "Mostrando registros del 0 al 0 de un total de 0 registros",
                "sInfoFiltered":   "(filtrado de un total de _MAX_ registros)",
                "sSearch":         "Buscar:",
                "oPaginate": {
                    "sFirst":    "Primero",
                    "sLast":     "Último",
                    "sNext":     "Siguiente",
                    "sPrevious": "Anterior"
                }
            }
        });

        // Alerta de éxito al crear/editar
        @if(session('success'))
            Swal.fire({
                title: '¡Éxito!',
                text: "{{ session('success') }}",
                icon: 'success',
                timer: 2500,
                showConfirmButton: false
            });
        @endif
    });

    // Confirmación AJAX para eliminar
    function confirmarEliminar(id, numero) {
        Swal.fire({
            title: '¿Eliminar habitación?',
            html: `¿Estás seguro de eliminar la habitación <strong>N° ${numero}</strong>? Esta acción no se puede deshacer.`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#6c757d',
            confirmButtonText: '<i class="fas fa-trash mr-1"></i> Sí, eliminar',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: "{{ url('habitaciones') }}/" + id,
                    type: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        _method: 'DELETE'
                    },
                    success: function(response) {
                        tabla.ajax.reload(null, false);
                        Swal.fire({
                            title: '¡Eliminada!',
                            text: response.message,
                            icon: 'success',
                            timer: 2000,
                            showConfirmButton: false
                        });
                    },
                    error: function() {
                        Swal.fire('Error', 'No se pudo eliminar la habitación. Es posible que tenga reservas asociadas.', 'error');
                    }
                });
            }
        });
    }
</script>
@endsection