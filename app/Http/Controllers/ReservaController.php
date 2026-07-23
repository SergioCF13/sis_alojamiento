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
        $this->middleware(function ($request, $next) {
            $user = auth()->user();
            if ($user && $user->role === 'Limpieza') {
                abort(403);
            }

            return $next($request);
        });
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $reservas = Reserva::with(['cliente', 'habitacion'])->select('reservas.*');

            $buscarCliente = trim((string) $request->input('buscar_cliente', ''));
            $buscarHabitacion = trim((string) $request->input('buscar_habitacion', ''));

            if ($buscarCliente !== '') {
                $reservas->whereHas('cliente', function ($query) use ($buscarCliente) {
                    $query->where('nombre_completo', 'like', "%{$buscarCliente}%")
                        ->orWhere('carnet_identidad', 'like', "%{$buscarCliente}%");
                });
            }

            if ($buscarHabitacion !== '') {
                $reservas->whereHas('habitacion', function ($query) use ($buscarHabitacion) {
                    $query->where('numero', 'like', "%{$buscarHabitacion}%");
                });
            }

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
                ->addColumn('searchable_text', function ($reserva) {
                    return implode(' ', [
                        $reserva->cliente?->nombre_completo ?? '',
                        $reserva->habitacion?->numero ?? '',
                        $reserva->fecha_ingreso,
                        $reserva->hora_ingreso,
                        $reserva->fecha_salida,
                        $reserva->hora_salida,
                        $reserva->estado,
                    ]);
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
                ->addColumn('estado_pago', function ($reserva) {
                    $tienePagos = $reserva->pagos->isNotEmpty();

                    $badgeClass = $tienePagos ? 'badge-success' : 'badge-warning';
                    $label = $tienePagos ? 'Pagada' : 'Pendiente';

                    return '<span class="badge ' . $badgeClass . ' px-2 py-1">' . e($label) . '</span>';
                })
                ->addColumn('acciones', function ($reserva) {
                    $btnVer = '<a href="' . route('reservas.show', $reserva->id) . '" class="btn btn-info btn-sm" title="Ver"><i class="fas fa-eye"></i></a>';
                    $buttons = $btnVer;

                    if (auth()->user() && auth()->user()->role === 'Administrador') {
                        $btnEditar = '<a href="' . route('reservas.edit', $reserva->id) . '" class="btn btn-warning btn-sm" title="Editar"><i class="fas fa-pencil-alt"></i></a>';
                        $btnEliminar = '<button type="button" class="btn btn-danger btn-sm" onclick="confirmarEliminar(' . $reserva->id . ', \'Reserva #' . $reserva->id . '\')" title="Eliminar"><i class="fas fa-trash"></i></button>';
                        $buttons .= ' ' . $btnEditar . ' ' . $btnEliminar;
                    }

                    $btnCheckin = '';
                    if ($reserva->estado !== 'Check-out' && $reserva->estado !== 'Cancelada' && auth()->user() && auth()->user()->role !== 'Limpieza') {
                        $btnCheckin = '<button type="button" class="btn btn-success btn-sm" onclick="cambiarEstado(' . $reserva->id . ', \'Check-in\')" title="Check-in"><i class="fas fa-sign-in-alt"></i></button>';
                    }

                    $btnCheckout = '';
                    if ($reserva->estado === 'Check-in' && auth()->user() && auth()->user()->role !== 'Limpieza') {
                        $btnCheckout = '<button type="button" class="btn btn-primary btn-sm" onclick="cambiarEstado(' . $reserva->id . ', \'Check-out\')" title="Check-out"><i class="fas fa-sign-out-alt"></i></button>';
                    }

                    return '<div class="btn-group btn-group-sm">' . $buttons . ' ' . $btnCheckin . ' ' . $btnCheckout . '</div>';
                })
                ->rawColumns(['cliente', 'habitacion', 'estado', 'estado_pago', 'acciones'])
                ->make(true);
        }

        return view('reservas.index');
    }

    public function create()
    {
        $clientes = Cliente::orderBy('nombre_completo')->get();
        $habitaciones = Habitacion::where('estado', 'Disponible')->orderBy('numero')->get();

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

        $reserva = Reserva::create($data);

        $this->syncHabitacionEstado($reserva);

        return redirect()->route('reservas.index')->with('success', 'Reserva registrada correctamente.');
    }

    public function show(Reserva $reserva)
    {
        $reserva->load(['cliente', 'habitacion.tipoHabitacion', 'pagos']);

        $montoTotal = 0;
        if ($reserva->habitacion && $reserva->habitacion->tipoHabitacion) {
            $montoTotal = (float) $reserva->habitacion->tipoHabitacion->precio;
        }

        $montoPagado = $reserva->pagos->sum('monto');
        $saldoPendiente = max($montoTotal - $montoPagado, 0);

        return view('reservas.show', compact('reserva', 'montoTotal', 'montoPagado', 'saldoPendiente'));
    }

    public function edit(Reserva $reserva)
    {
        $clientes = Cliente::orderBy('nombre_completo')->get();
        $habitaciones = Habitacion::where('estado', 'Disponible')->orderBy('numero')->get();

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

        $this->syncHabitacionEstado($reserva);

        return redirect()->route('reservas.index')->with('success', 'Reserva actualizada correctamente.');
    }

    public function cambiarEstado(Request $request, Reserva $reserva)
    {
        $estado = $request->validate([
            'estado' => ['required', 'in:Check-in,Check-out'],
        ])['estado'];

        $data = ['estado' => $estado];

        if ($estado === 'Check-in') {
            $data['fecha_ingreso'] = now()->toDateString();
            $data['hora_ingreso'] = now()->format('H:i');
        }

        if ($estado === 'Check-out') {
            $data['fecha_salida'] = now()->toDateString();
            $data['hora_salida'] = now()->format('H:i');
        }

        $reserva->update($data);
        $this->syncHabitacionEstado($reserva);

        return response()->json(['success' => true, 'message' => 'Estado actualizado correctamente.']);
    }

    public function destroy(Reserva $reserva)
    {
        $this->syncHabitacionEstadoOnDelete($reserva);
        $reserva->delete();

        return response()->json(['success' => true, 'message' => 'Reserva eliminada correctamente.']);
    }

    private function syncHabitacionEstado(Reserva $reserva): void
    {
        if (! $reserva->habitacion_id) {
            return;
        }

        $habitacion = $reserva->habitacion;
        if (! $habitacion) {
            return;
        }

        $estado = match ($reserva->estado) {
            'Check-in' => 'Ocupada',
            'Check-out' => 'Limpieza',
            'Cancelada' => 'Disponible',
            default => $habitacion->estado,
        };

        $habitacion->update(['estado' => $estado]);
    }

    private function syncHabitacionEstadoOnDelete(Reserva $reserva): void
    {
        if (! $reserva->habitacion_id) {
            return;
        }

        $habitacion = $reserva->habitacion;
        if (! $habitacion) {
            return;
        }

        if ($habitacion->estado === 'Ocupada') {
            $habitacion->update(['estado' => 'Disponible']);
        }
    }
}
