<?php

namespace App\Http\Controllers;

use App\Models\TipoHabitacion;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class TipoHabitacionController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware(function ($request, $next) {
            $user = auth()->user();
            if ($user && in_array($user->role, ['Recepcionista', 'Limpieza']) && in_array($request->route()->getActionMethod(), ['create', 'store', 'edit', 'update', 'destroy'])) {
                abort(403);
            }

            return $next($request);
        });
    }

    /**
     * Mostrar todos los tipos de habitación con DataTables.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $tipos = TipoHabitacion::select(['id', 'nombre', 'descripcion', 'precio']);

            return DataTables::of($tipos)
                ->editColumn('descripcion', function ($tipo) {
                    return $tipo->descripcion 
                        ? e($tipo->descripcion) 
                        : '<span class="text-muted italic">Sin descripción</span>';
                })
                ->editColumn('precio', function ($tipo) {
                    return '<span class="badge badge-success font-weight-bold">Bs ' . number_format($tipo->precio, 2) . '</span>';
                })
                ->addColumn('acciones', function ($tipo) {
                    $btnVer = '<a href="' . route('tipo_habitaciones.show', $tipo) . '" class="btn btn-info btn-sm" title="Ver"><i class="fas fa-eye"></i></a>';
                    $buttons = $btnVer;

                    if (auth()->user() && auth()->user()->role === 'Administrador') {
                        $btnEditar = '<a href="' . route('tipo_habitaciones.edit', $tipo) . '" class="btn btn-warning btn-sm" title="Editar"><i class="fas fa-pencil-alt"></i></a>';
                        $btnEliminar = '<button type="button" class="btn btn-danger btn-sm" onclick="confirmarEliminar(' . $tipo->id . ', \'' . e($tipo->nombre) . '\')" title="Eliminar"><i class="fas fa-trash"></i></button>';
                        $buttons .= ' ' . $btnEditar . ' ' . $btnEliminar;
                    }

                    return '<div class="btn-group btn-group-sm">' . $buttons . '</div>';
                })
                ->rawColumns(['descripcion', 'precio', 'acciones'])
                ->make(true);
        }

        return view('tipo_habitaciones.index');
    }

    /**
     * Mostrar el formulario para crear un nuevo tipo de habitación.
     */
    public function create()
    {
        return view('tipo_habitaciones.create');
    }

    /**
     * Guardar un nuevo tipo de habitación.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'nombre'      => 'required|string|max:100|unique:tipo_habitaciones,nombre', // <-- Corregido aquí
            'descripcion' => 'nullable|string',
            'precio'      => 'required|numeric|min:0',
        ]);

        TipoHabitacion::create($data);

        return redirect()->route('tipo_habitaciones.index')
            ->with('success', 'Tipo de habitación registrado correctamente.');
    }

    /**
     * Mostrar un tipo de habitación.
     */
    public function show(TipoHabitacion $tipoHabitacion)
    {
        return view('tipo_habitaciones.show', compact('tipoHabitacion'));
    }

    /**
     * Mostrar el formulario para editar.
     */
    public function edit(TipoHabitacion $tipoHabitacion)
    {
        return view('tipo_habitaciones.edit', compact('tipoHabitacion'));
    }

    /**
     * Actualizar un tipo de habitación.
     */
    public function update(Request $request, TipoHabitacion $tipoHabitacion)
    {
        $data = $request->validate([
            'nombre'      => 'required|string|max:100|unique:tipo_habitaciones,nombre,' . $tipoHabitacion->id, // <-- Corregido aquí
            'descripcion' => 'nullable|string',
            'precio'      => 'required|numeric|min:0',
        ]);

        $tipoHabitacion->update($data);

        return redirect()->route('tipo_habitaciones.index')
            ->with('success', 'Tipo de habitación actualizado correctamente.');
    }

    /**
     * Eliminar un tipo de habitación vía AJAX.
     */
    public function destroy(TipoHabitacion $tipoHabitacion)
    {
        $tipoHabitacion->delete();

        return response()->json([
            'success' => true, 
            'message' => 'Tipo de habitación eliminado correctamente.'
        ]);
    }
}