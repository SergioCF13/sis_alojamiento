<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use App\Models\Habitacion;
use App\Models\Reserva;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class ReservaController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $reservas = Reserva::with(['cliente', 'habitacion'])->select('reservas.*');

            return DataTables::of($reservas)
                ->addColumn('cliente', function ($reserva) {
                    return $reserva->cliente ? e($reserva->cliente->nombre_completo) : '<span class="text-muted">Sin cliente</span>';
                })
                ->addColumn('habitacion', function ($reserva) {
                    return $reserva->habitacion ? e($reserva->habitacion->numero) : '<span class="text-muted">Sin habitación</span>';
                })
                ->addColumn('fecha', function ($reserva) {
                    return e($reserva->fecha_ingreso . ' ' . $reserva->hora_ingreso . ' / ' . $reserva->fecha_salida . ' ' . $reserva->hora_salida);
                })
                ->editColumn('estado', function ($reserva) {
                    $badgeClass = match ($reserva->estado) {
                        'Check-in' => 'badge-success',
                        'Check-out' => 'badge-info',
                        'Cancelada' => 'badge-danger',
                        default => 'badge-warning',
                    };

                    return '<span class="badge ' . $badgeClass . ' px-2 py-1">' . e($reserva->estado) . '</span>';
                })
                ->addColumn('acciones', function ($reserva) {
                    $btnVer = '<a href="' . route('reservas.show', $reserva->id) . '" class="btn btn-info btn-sm" title="Ver"><i class="fas fa-eye"></i></a>';
                    $btnEditar = '<a href="' . route('reservas.edit', $reserva->id) . '" class="btn btn-warning btn-sm" title="Editar"><i class="fas fa-pencil-alt"></i></a>';
                    $btnEliminar = '<button type="button" class="btn btn-danger btn-sm" onclick="confirmarEliminar(' . $reserva->id . ', \'Reserva #' . $reserva->id . '\')" title="Eliminar"><i class="fas fa-trash"></i></button>';

                    return '<div class="btn-group btn-group-sm">' . $btnVer . ' ' . $btnEditar . ' ' . $btnEliminar . '</div>';
                })
                ->rawColumns(['cliente', 'habitacion', 'estado', 'acciones'])
                ->make(true);
        }

        return view('reservas.index');
    }

    public function create()
    {
        $clientes = Cliente::orderBy('nombre_completo')->get();
        $habitaciones = Habitacion::orderBy('numero')->get();

        return view('reservas.create', compact('clientes', 'habitaciones'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'cliente_id' => ['required', 'exists:clientes,id'],
            'habitacion_id' => ['nullable', 'exists:habitaciones,id'],
            'fecha_ingreso' => ['required', 'date'],
            'hora_ingreso' => ['required'],
            'fecha_salida' => ['required', 'date', 'after_or_equal:fecha_ingreso'],
            'hora_salida' => ['required'],
            'cantidad_persona' => ['required', 'integer', 'min:1'],
            'estado' => ['required', 'in:Reserva,Check-in,Check-out,Cancelada'],
            'observaciones' => ['nullable', 'string'],
        ]);

        Reserva::create($data);

        return redirect()->route('reservas.index')->with('success', 'Reserva registrada correctamente.');
    }

    public function show(Reserva $reserva)
    {
        $reserva->load(['cliente', 'habitacion']);

        return view('reservas.show', compact('reserva'));
    }

    public function edit(Reserva $reserva)
    {
        $clientes = Cliente::orderBy('nombre_completo')->get();
        $habitaciones = Habitacion::orderBy('numero')->get();

        return view('reservas.edit', compact('reserva', 'clientes', 'habitaciones'));
    }

    public function update(Request $request, Reserva $reserva)
    {
        $data = $request->validate([
            'cliente_id' => ['required', 'exists:clientes,id'],
            'habitacion_id' => ['nullable', 'exists:habitaciones,id'],
            'fecha_ingreso' => ['required', 'date'],
            'hora_ingreso' => ['required'],
            'fecha_salida' => ['required', 'date', 'after_or_equal:fecha_ingreso'],
            'hora_salida' => ['required'],
            'cantidad_persona' => ['required', 'integer', 'min:1'],
            'estado' => ['required', 'in:Reserva,Check-in,Check-out,Cancelada'],
            'observaciones' => ['nullable', 'string'],
        ]);

        $reserva->update($data);

        return redirect()->route('reservas.index')->with('success', 'Reserva actualizada correctamente.');
    }

    public function destroy(Reserva $reserva)
    {
        $reserva->delete();

        return response()->json(['success' => true, 'message' => 'Reserva eliminada correctamente.']);
    }
}
