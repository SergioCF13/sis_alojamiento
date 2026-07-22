@extends('adminlte::page')

@section('title', 'Lista de Clientes')

@section('plugins.Datatables', true)
@section('plugins.DatatablesPlugin', true)
@section('plugins.Sweetalert2', true)

@section('content_header')
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Gestión de Clientes</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Inicio</a></li>
                    <li class="breadcrumb-item active">Clientes</li>
                </ol>
            </div>
        </div>
    </div>
@endsection

@section('content')
<div class="container-fluid">
    <div class="card card-primary card-outline">
        <div class="card-header">
            <h3 class="card-title mt-1">
                <i class="fas fa-users mr-1"></i> Listado de Clientes
            </h3>
            <div class="card-tools">
                <a href="{{ route('clientes.create') }}" class="btn btn-primary btn-sm">
                    <i class="fas fa-user-plus mr-1"></i> Nuevo cliente
                </a>
            </div>
        </div>

        <div class="card-body">
            <table id="tabla-clientes" class="table table-striped table-bordered table-hover w-100">
                <thead class="thead-light">
                    <tr>
                        <th>Nombre completo</th>
                        <th>Carnet</th>
                        <th>Celular</th>
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
        // Inicialización de DataTable
        tabla = $('#tabla-clientes').DataTable({
            "processing": true,
            "serverSide": true,
            "ajax": "{{ route('clientes.index') }}",
            "columns": [
                { data: 'nombre_completo', name: 'nombre_completo' },
                { data: 'carnet_identidad', name: 'carnet_identidad' },
                { data: 'celular', name: 'celular' },
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

        // MODAL DE ÉXITO AL REDIRIGIR (Crear o Actualizar)
        @if(session('success'))
            Swal.fire({
                title: '¡Éxito!',
                text: "{{ session('success') }}",
                type: 'success', // Usamos 'type' para ser compatible con tu versión de SweetAlert
                icon: 'success', // Mantenemos compatibilidad por si actualizas
                timer: 2500,
                showConfirmButton: false
            });
        @endif
    });

    // MODAL DE CONFIRMACIÓN AL ELIMINAR
    function confirmarEliminar(id, nombre) {
        Swal.fire({
            title: '¿Eliminar cliente?',
            html: `¿Estás seguro de eliminar a <strong>${nombre}</strong>? Esta acción no se puede deshacer.`,
            type: 'warning',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#6c757d',
            confirmButtonText: '<i class="fas fa-trash mr-1"></i> Sí, eliminar',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.value || result.isConfirmed) {
                $.ajax({
                    url: `/clientes/${id}`,
                    type: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        _method: 'DELETE'
                    },
                    success: function(response) {
                        tabla.ajax.reload(null, false);
                        
                        // Modal para "Cliente eliminado correctamente."
                        Swal.fire({
                            title: '¡Eliminado!',
                            text: response.message,
                            type: 'success',
                            icon: 'success',
                            timer: 2000,
                            showConfirmButton: false
                        });
                    },
                    error: function() {
                        Swal.fire('Error', 'No se pudo eliminar el cliente.', 'error');
                    }
                });
            }
        });
    }
</script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@endsection