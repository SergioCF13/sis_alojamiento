@extends('adminlte::page')

@section('title', 'Tipos de Habitación')

@section('plugins.Datatables', true)
@section('plugins.DatatablesPlugin', true)
@section('plugins.Sweetalert2', true)

@section('content_header')
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Tipos de Habitación</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Inicio</a></li>
                    <li class="breadcrumb-item active">Tipos de Habitación</li>
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
                <i class="fas fa-bed mr-1"></i> Listado de Tipos de Habitación
            </h3>
            <div class="card-tools">
                <a href="{{ route('tipo_habitaciones.create') }}" class="btn btn-navy btn-sm" style="background-color: #001f3f; color: #fff;">
                    <i class="fas fa-plus mr-1"></i> Nuevo Tipo
                </a>
            </div>
        </div>

        <div class="card-body">
            <table id="tabla-tipos" class="table table-striped table-bordered table-hover w-100">
                <thead class="thead-light">
                    <tr>
                        <th>Nombre</th>
                        <th>Descripción</th>
                        <th class="text-center">Precio</th>
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
        tabla = $('#tabla-tipos').DataTable({
            "processing": true,
            "serverSide": true,
            "ajax": "{{ route('tipo_habitaciones.index') }}",
            "columns": [
                { data: 'nombre', name: 'nombre' },
                { data: 'descripcion', name: 'descripcion' },
                { data: 'precio', name: 'precio', className: 'text-center align-middle' },
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

        // Alerta al redirigir (Crear o Actualizar)
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
    function confirmarEliminar(id, nombre) {
        Swal.fire({
            title: '¿Eliminar tipo de habitación?',
            html: `¿Estás seguro de eliminar <strong>${nombre}</strong>? Esta acción no se puede deshacer.`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#6c757d',
            confirmButtonText: '<i class="fas fa-trash mr-1"></i> Sí, eliminar',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: "{{ url('tipo_habitaciones') }}/" + id,
                    type: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        _method: 'DELETE'
                    },
                    success: function(response) {
                        tabla.ajax.reload(null, false);
                        Swal.fire({
                            title: '¡Eliminado!',
                            text: response.message,
                            icon: 'success',
                            timer: 2000,
                            showConfirmButton: false
                        });
                    },
                    error: function() {
                        Swal.fire('Error', 'No se pudo eliminar el registro. Puede que esté asociado a una habitación.', 'error');
                    }
                });
            }
        });
    }
</script>
@endsection