<?php

namespace App\Http\Controllers;

use App\Models\Pago;
use App\Models\Reserva;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class PagoController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware(function ($request, $next) {
            $user = auth()->user();
            if ($user && $user->role === 'Administrador') {
                return $next($request);
            }

            if ($user && $user->role === 'Limpieza') {
                abort(403);
            }

            if ($user && $user->role === 'Recepcionista' && in_array($request->route()->getActionMethod(), ['edit', 'update', 'destroy'])) {
                abort(403);
            }

            return $next($request);
        });
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $pagos = Pago::with('reserva.cliente')->select('pagos.*');

            return DataTables::of($pagos)
                ->addColumn('cliente', function ($pago) {
                    return $pago->reserva && $pago->reserva->cliente
                        ? e($pago->reserva->cliente->nombre_completo)
                        : '<span class="text-muted">Sin cliente</span>';
                })
                ->addColumn('reserva', function ($pago) {
                    return $pago->reserva ? 'Reserva #' . $pago->reserva->id : '-';
                })
                ->editColumn('monto', function ($pago) {
                    return 'Bs ' . number_format($pago->monto, 2);
                })
                ->editColumn('estado', function ($pago) {
                    $badgeClass = $pago->estado === 'Pagado' ? 'badge-success' : 'badge-warning';
                    return '<span class="badge ' . $badgeClass . ' px-2 py-1">' . e($pago->estado) . '</span>';
                })
                ->addColumn('acciones', function ($pago) {
                    $btnVer = '<a href="' . route('pagos.show', $pago->id) . '" class="btn btn-info btn-sm" title="Ver"><i class="fas fa-eye"></i></a>';
                    $buttons = $btnVer;

                    if (auth()->user() && auth()->user()->role === 'Administrador') {
                        $btnEditar = '<a href="' . route('pagos.edit', $pago->id) . '" class="btn btn-warning btn-sm" title="Editar"><i class="fas fa-pencil-alt"></i></a>';
                        $btnEliminar = '<button type="button" class="btn btn-danger btn-sm" onclick="confirmarEliminar(' . $pago->id . ', \'Pago #' . $pago->id . '\')" title="Eliminar"><i class="fas fa-trash"></i></button>';
                        $buttons .= ' ' . $btnEditar . ' ' . $btnEliminar;
                    }

                    return '<div class="btn-group btn-group-sm">' . $buttons . '</div>';
                })
                ->rawColumns(['cliente', 'estado', 'acciones'])
                ->make(true);
        }

        return view('pagos.index');
    }

    public function create(Request $request)
    {
        $reservas = Reserva::with(['cliente', 'habitacion.tipoHabitacion', 'pagos'])->orderBy('id', 'desc')->get();
        $selectedReservaId = $request->query('reserva_id');

        $saldoPendiente = null;
        if ($selectedReservaId) {
            $reserva = $reservas->firstWhere('id', $selectedReservaId);
            if ($reserva) {
                $montoTotal = $reserva->habitacion && $reserva->habitacion->tipoHabitacion
                    ? (float) $reserva->habitacion->tipoHabitacion->precio
                    : 0;
                $montoPagado = (float) $reserva->pagos->sum('monto');
                $saldoPendiente = max($montoTotal - $montoPagado, 0);
            }
        }

        return view('pagos.create', compact('reservas', 'selectedReservaId', 'saldoPendiente'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'reserva_id' => ['required', 'exists:reservas,id'],
            'monto' => ['required', 'numeric', 'min:0'],
            'metodo_pago' => ['required', 'in:Efectivo,Transferencia,QR,Tarjeta'],
            'estado' => ['required', 'in:Pagado,Pendiente'],
            'fecha_pago' => ['required', 'date'],
            'observaciones' => ['nullable', 'string'],
        ]);

        Pago::create($data);

        return redirect()->route('pagos.index')->with('success', 'Pago registrado correctamente.');
    }

    public function show(Pago $pago)
    {
        $pago->load('reserva.cliente');

        return view('pagos.show', compact('pago'));
    }

    public function edit(Pago $pago)
    {
        $reservas = Reserva::with('cliente')->orderBy('id', 'desc')->get();

        return view('pagos.edit', compact('pago', 'reservas'));
    }

    public function update(Request $request, Pago $pago)
    {
        $data = $request->validate([
            'reserva_id' => ['required', 'exists:reservas,id'],
            'monto' => ['required', 'numeric', 'min:0'],
            'metodo_pago' => ['required', 'in:Efectivo,Transferencia,QR,Tarjeta'],
            'estado' => ['required', 'in:Pagado,Pendiente'],
            'fecha_pago' => ['required', 'date'],
            'observaciones' => ['nullable', 'string'],
        ]);

        $pago->update($data);

        return redirect()->route('pagos.index')->with('success', 'Pago actualizado correctamente.');
    }

    public function destroy(Pago $pago)
    {
        $pago->delete();

        return response()->json(['success' => true, 'message' => 'Pago eliminado correctamente.']);
    }
}
