<?php

namespace App\Http\Controllers;

use App\Models\Habitacion;
use App\Models\TipoHabitacion;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class HabitacionController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Mostrar todas las habitaciones con DataTables.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $habitaciones = Habitacion::with('tipoHabitacion')->select('habitaciones.*');

            return DataTables::of($habitaciones)
                ->addColumn('tipo_habitacion', function ($habitacion) {
                    return $habitacion->tipoHabitacion 
                        ? e($habitacion->tipoHabitacion->nombre) 
                        : '<span class="text-muted">Sin tipo</span>';
                })
                ->editColumn('piso', function ($habitacion) {
                    return 'Piso ' . $habitacion->piso;
                })
                ->editColumn('estado', function ($habitacion) {
                    $badgeClass = match ($habitacion->estado) {
                        'Disponible'    => 'badge-success',
                        'Ocupada'       => 'badge-danger',
                        'Limpieza'      => 'badge-warning',
                        'Mantenimiento' => 'badge-secondary',
                        default         => 'badge-info',
                    };

                    return '<span class="badge ' . $badgeClass . ' px-2 py-1">' . $habitacion->estado . '</span>';
                })
                ->addColumn('acciones', function ($habitacion) {
                    $btnVer = '<a href="' . route('habitaciones.show', $habitacion->id) . '" class="btn btn-info btn-sm" title="Ver"><i class="fas fa-eye"></i></a>';
                    $btnEditar = '<a href="' . route('habitaciones.edit', $habitacion->id) . '" class="btn btn-warning btn-sm" title="Editar"><i class="fas fa-pencil-alt"></i></a>';
                    $btnEliminar = '<button type="button" class="btn btn-danger btn-sm" onclick="confirmarEliminar(' . $habitacion->id . ', \'' . e($habitacion->numero) . '\')" title="Eliminar"><i class="fas fa-trash"></i></button>';

                    return '<div class="btn-group btn-group-sm">' . $btnVer . ' ' . $btnEditar . ' ' . $btnEliminar . '</div>';
                })
                ->rawColumns(['tipo_habitacion', 'estado', 'acciones'])
                ->make(true);
        }

        return view('habitaciones.index');
    }

    /**
     * Mostrar el formulario para crear una habitación.
     */
    public function create()
    {
        $tipoHabitaciones = TipoHabitacion::all();

        return view('habitaciones.create', compact('tipoHabitaciones'));
    }

    /**
     * Guardar una nueva habitación.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'numero'             => 'required|string|max:10|unique:habitaciones,numero',
            'piso'               => 'required|integer|min:1',
            'estado'             => 'required|in:Disponible,Ocupada,Limpieza,Mantenimiento',
            'tipo_habitacion_id' => 'required|exists:tipo_habitaciones,id',
        ]);

        Habitacion::create($data);

        return redirect()->route('habitaciones.index')
            ->with('success', 'Habitación registrada correctamente.');
    }

    /**
     * Mostrar una habitación.
     */
    public function show(Habitacion $habitacion)
    {
        $habitacion->load('tipoHabitacion');

        return view('habitaciones.show', compact('habitacion'));
    }

    /**
     * Mostrar el formulario para editar una habitación.
     */
    public function edit(Habitacion $habitacion)
    {
        $tipoHabitaciones = TipoHabitacion::all();

        return view('habitaciones.edit', compact('habitacion', 'tipoHabitaciones'));
    }

    /**
     * Actualizar una habitación.
     */
    public function update(Request $request, Habitacion $habitacion)
    {
        $data = $request->validate([
            'numero'             => 'required|string|max:10|unique:habitaciones,numero,' . $habitacion->id,
            'piso'               => 'required|integer|min:1',
            'estado'             => 'required|in:Disponible,Ocupada,Limpieza,Mantenimiento',
            'tipo_habitacion_id' => 'required|exists:tipo_habitaciones,id',
        ]);

        $habitacion->update($data);

        return redirect()->route('habitaciones.index')
            ->with('success', 'Habitación actualizada correctamente.');
    }

    /**
     * Eliminar una habitación vía AJAX.
     */
    public function destroy(Habitacion $habitacion)
    {
        $habitacion->delete();

        return response()->json([
            'success' => true,
            'message' => 'Habitación eliminada correctamente.'
        ]);
    }
}