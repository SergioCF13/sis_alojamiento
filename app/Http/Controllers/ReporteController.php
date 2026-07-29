<?php

namespace App\Http\Controllers;

use App\Models\Reserva;
use App\Models\Pago;
use App\Models\Habitacion;
use App\Models\Cliente;
use Illuminate\Support\Facades\DB;

class ReporteController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $totalReservas = Reserva::count();
        $totalPagos = Pago::count();
        $habitacionesDisponibles = Habitacion::where('estado', 'Disponible')->count();
        $habitacionesOcupadas = Habitacion::where('estado', 'Ocupada')->count();

        $pagosHoy = Pago::whereDate('fecha_pago', today())->sum('monto');
        $pagosMes = Pago::whereMonth('fecha_pago', now()->month)
            ->whereYear('fecha_pago', now()->year)
            ->sum('monto');

        $reservasPorEstado = Reserva::select('estado', DB::raw('count(*) as total'))
            ->groupBy('estado')
            ->pluck('total', 'estado')
            ->toArray();

        $habitacionesPorEstado = Habitacion::select('estado', DB::raw('count(*) as total'))
            ->groupBy('estado')
            ->pluck('total', 'estado')
            ->toArray();

        $pagosUltimos7Dias = Pago::select(DB::raw('DATE(fecha_pago) as fecha'), DB::raw('SUM(monto) as total'))
            ->where('fecha_pago', '>=', now()->subDays(6)->startOfDay())
            ->groupBy(DB::raw('DATE(fecha_pago)'))
            ->orderBy('fecha')
            ->get();

        $clientesTop = Reserva::select('cliente_id', DB::raw('count(*) as total_reservas'))
            ->with('cliente')
            ->groupBy('cliente_id')
            ->orderByDesc('total_reservas')
            ->limit(5)
            ->get();

        $pagosPorMetodo = Pago::select('metodo_pago', DB::raw('count(*) as total'))
            ->groupBy('metodo_pago')
            ->pluck('total', 'metodo_pago')
            ->toArray();

        return view('reportes.index', compact(
            'totalReservas',
            'totalPagos',
            'habitacionesDisponibles',
            'habitacionesOcupadas',
            'pagosHoy',
            'pagosMes',
            'reservasPorEstado',
            'habitacionesPorEstado',
            'pagosUltimos7Dias',
            'clientesTop',
            'pagosPorMetodo'
        ));
    }
}
