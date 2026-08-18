<?php

namespace App\Http\Controllers;

use App\Models\Reserva;
use App\Models\Pago;
use App\Models\Habitacion;
use App\Models\Cliente;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReporteController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        [$fechaInicio, $fechaFin] = $this->obtenerRangoFechas($request);

        $reservasEnRango = Reserva::whereBetween('fecha_ingreso', [$fechaInicio, $fechaFin])
            ->orWhereBetween('fecha_salida', [$fechaInicio, $fechaFin]);

        $pagosEnRango = Pago::whereBetween('fecha_pago', [$fechaInicio, $fechaFin]);

        $totalReservas = $reservasEnRango->count();
        $totalPagos = $pagosEnRango->count();
        $montoPagos = (float) $pagosEnRango->sum('monto');

        $habitacionesDisponibles = Habitacion::where('estado', 'Disponible')->count();
        $habitacionesOcupadas = Habitacion::where('estado', 'Ocupada')->count();
        $habitacionesTotales = Habitacion::count();
        $clientesTotales = Cliente::count();

        $clientesEnRango = Cliente::whereBetween('created_at', [$fechaInicio . ' 00:00:00', $fechaFin . ' 23:59:59'])->count();
        $habitacionesEnRango = Habitacion::whereBetween('created_at', [$fechaInicio . ' 00:00:00', $fechaFin . ' 23:59:59'])->count();

        $pagosHoy = Pago::whereDate('fecha_pago', today())->sum('monto');
        $pagosDelDiaCantidad = Pago::whereDate('fecha_pago', today())->count();
        $pagosDelDiaMonto = Pago::whereDate('fecha_pago', today())->sum('monto');
        $reservasDelDia = Reserva::whereDate('fecha_ingreso', today())
            ->orWhereDate('fecha_salida', today())
            ->count();
        $pagosMes = Pago::whereMonth('fecha_pago', now()->month)
            ->whereYear('fecha_pago', now()->year)
            ->sum('monto');

        $reservasPorEstado = Reserva::whereBetween('fecha_ingreso', [$fechaInicio, $fechaFin])
            ->orWhereBetween('fecha_salida', [$fechaInicio, $fechaFin])
            ->select('estado', DB::raw('count(*) as total'))
            ->groupBy('estado')
            ->pluck('total', 'estado')
            ->toArray();

        $habitacionesPorEstado = Habitacion::whereHas('reservas', function ($query) use ($fechaInicio, $fechaFin) {
            $query->whereBetween('fecha_ingreso', [$fechaInicio, $fechaFin])
                ->orWhereBetween('fecha_salida', [$fechaInicio, $fechaFin]);
        })->select('estado', DB::raw('count(*) as total'))
            ->groupBy('estado')
            ->pluck('total', 'estado')
            ->toArray();

        $pagosUltimos7Dias = Pago::select(DB::raw('DATE(fecha_pago) as fecha'), DB::raw('SUM(monto) as total'))
            ->whereBetween('fecha_pago', [$fechaInicio, $fechaFin])
            ->groupBy(DB::raw('DATE(fecha_pago)'))
            ->orderBy('fecha')
            ->get();

        $clientesTop = Reserva::whereBetween('fecha_ingreso', [$fechaInicio, $fechaFin])
            ->orWhereBetween('fecha_salida', [$fechaInicio, $fechaFin])
            ->select('cliente_id', DB::raw('count(*) as total_reservas'))
            ->with('cliente')
            ->groupBy('cliente_id')
            ->orderByDesc('total_reservas')
            ->limit(5)
            ->get();

        $pagosPorMetodo = $pagosEnRango->select('metodo_pago', DB::raw('count(*) as total'))
            ->groupBy('metodo_pago')
            ->pluck('total', 'metodo_pago')
            ->toArray();

        return view('reportes.index', compact(
            'fechaInicio',
            'fechaFin',
            'clientesEnRango',
            'habitacionesEnRango',
            'totalReservas',
            'totalPagos',
            'montoPagos',
            'habitacionesTotales',
            'clientesTotales',
            'habitacionesDisponibles',
            'habitacionesOcupadas',
            'pagosHoy',
            'pagosDelDiaCantidad',
            'pagosDelDiaMonto',
            'reservasDelDia',
            'pagosMes',
            'reservasPorEstado',
            'habitacionesPorEstado',
            'pagosUltimos7Dias',
            'clientesTop',
            'pagosPorMetodo'
        ));
    }

    public function pdf(Request $request)
    {
        [$fechaInicio, $fechaFin] = $this->obtenerRangoFechas($request);

        return $this->exportarTipo('general', $fechaInicio, $fechaFin);
    }

    public function pdfPorTipo(Request $request, $tipo)
    {
        [$fechaInicio, $fechaFin] = $this->obtenerRangoFechas($request);

        return $this->exportarTipo($tipo, $fechaInicio, $fechaFin);
    }

    private function exportarTipo($tipo, $fechaInicio, $fechaFin)
    {
        $reservasEnRango = Reserva::whereBetween('fecha_ingreso', [$fechaInicio, $fechaFin])
            ->orWhereBetween('fecha_salida', [$fechaInicio, $fechaFin])
            ->with(['cliente', 'habitacion.tipoHabitacion'])
            ->get();

        $pagosEnRango = Pago::whereBetween('fecha_pago', [$fechaInicio, $fechaFin])
            ->with('reserva.cliente')
            ->get();

        $clientesEnRango = Cliente::whereHas('reservas', function ($query) use ($fechaInicio, $fechaFin) {
            $query->whereBetween('fecha_ingreso', [$fechaInicio, $fechaFin])
                ->orWhereBetween('fecha_salida', [$fechaInicio, $fechaFin]);
        })->get();

        $habitacionesEnRango = Habitacion::whereHas('reservas', function ($query) use ($fechaInicio, $fechaFin) {
            $query->whereBetween('fecha_ingreso', [$fechaInicio, $fechaFin])
                ->orWhereBetween('fecha_salida', [$fechaInicio, $fechaFin]);
        })->with('tipoHabitacion')->get();

        $clientesEnRangoCount = $clientesEnRango->count();
        $habitacionesEnRangoCount = $habitacionesEnRango->count();

        $totalReservas = $reservasEnRango->count();
        $totalPagos = $pagosEnRango->count();
        $montoPagos = $pagosEnRango->sum('monto');
        $reservasPorEstado = Reserva::whereBetween('fecha_ingreso', [$fechaInicio, $fechaFin])
            ->orWhereBetween('fecha_salida', [$fechaInicio, $fechaFin])
            ->select('estado', DB::raw('count(*) as total'))
            ->groupBy('estado')
            ->pluck('total', 'estado')
            ->toArray();

        $view = 'reportes.pdf';
        if (in_array($tipo, ['clientes', 'habitaciones', 'pagos', 'reservas'], true)) {
            $view = 'reportes.' . $tipo;
        }

        $pdf = Pdf::loadView($view, compact(
            'tipo',
            'fechaInicio',
            'fechaFin',
            'clientesEnRango',
            'clientesEnRangoCount',
            'habitacionesEnRango',
            'habitacionesEnRangoCount',
            'totalReservas',
            'totalPagos',
            'montoPagos',
            'reservasPorEstado',
            'reservasEnRango',
            'pagosEnRango'
        ));

        $filename = 'reporte-' . ($tipo === 'general' ? 'general' : $tipo) . '-' . $fechaInicio . '-al-' . $fechaFin . '.pdf';

        return $pdf->download($filename);
    }

    private function obtenerRangoFechas(Request $request): array
    {
        $fechaInicio = $request->filled('fecha_inicio')
            ? $request->date('fecha_inicio')->toDateString()
            : now()->subDays(30)->toDateString();

        $fechaFin = $request->filled('fecha_fin')
            ? $request->date('fecha_fin')->toDateString()
            : now()->toDateString();

        if ($fechaFin < $fechaInicio) {
            [$fechaInicio, $fechaFin] = [$fechaFin, $fechaInicio];
        }

        return [$fechaInicio, $fechaFin];
    }
}
