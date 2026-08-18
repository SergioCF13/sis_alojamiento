<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use App\Models\Habitacion;
use App\Models\Pago;
use App\Models\Reserva;
use Carbon\Carbon;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Request $request)
    {
        $fechaInicio = $request->filled('fecha_inicio')
            ? Carbon::parse($request->fecha_inicio)->toDateString()
            : now()->subDays(30)->toDateString();

        $fechaFin = $request->filled('fecha_fin')
            ? Carbon::parse($request->fecha_fin)->toDateString()
            : now()->toDateString();

        if ($fechaFin < $fechaInicio) {
            [$fechaInicio, $fechaFin] = [$fechaFin, $fechaInicio];
        }

        $clientesTotal = Cliente::count();
        $clientesRango = Cliente::whereHas('reservas', function ($query) use ($fechaInicio, $fechaFin) {
            $query->whereBetween('fecha_ingreso', [$fechaInicio, $fechaFin])
                ->orWhereBetween('fecha_salida', [$fechaInicio, $fechaFin]);
        })->get();

        $habitacionesTotal = Habitacion::count();
        $habitacionesDisponibles = Habitacion::where('estado', 'Disponible')->count();
        $habitacionesOcupadas = Habitacion::where('estado', 'Ocupada')->count();
        $habitacionesRango = Habitacion::whereHas('reservas', function ($query) use ($fechaInicio, $fechaFin) {
            $query->whereBetween('fecha_ingreso', [$fechaInicio, $fechaFin])
                ->orWhereBetween('fecha_salida', [$fechaInicio, $fechaFin]);
        })->with('tipoHabitacion')->get();

        $pagosRango = Pago::whereBetween('fecha_pago', [$fechaInicio, $fechaFin])->with('reserva.cliente')->get();
        $pagosTotal = $pagosRango->count();
        $pagosMonto = (float) $pagosRango->sum('monto');
        $pagosHoy = Pago::whereDate('fecha_pago', today())->get();
        $pagosHoyMonto = (float) $pagosHoy->sum('monto');
        $pagosHoyCantidad = $pagosHoy->count();

        $reservasRango = Reserva::whereBetween('fecha_ingreso', [$fechaInicio, $fechaFin])
            ->orWhereBetween('fecha_salida', [$fechaInicio, $fechaFin])
            ->with(['cliente', 'habitacion'])
            ->get();
        $reservasTotal = $reservasRango->count();
        $reservasHoy = Reserva::whereDate('fecha_ingreso', today())
            ->orWhereDate('fecha_salida', today())
            ->with(['cliente', 'habitacion'])
            ->get();
        $reservasHoyCantidad = $reservasHoy->count();

        $data = compact(
            'fechaInicio',
            'fechaFin',
            'clientesTotal',
            'clientesRango',
            'habitacionesTotal',
            'habitacionesDisponibles',
            'habitacionesOcupadas',
            'habitacionesRango',
            'pagosRango',
            'pagosTotal',
            'pagosMonto',
            'pagosHoyMonto',
            'pagosHoyCantidad',
            'reservasRango',
            'reservasTotal',
            'reservasHoy',
            'reservasHoyCantidad'
        );

        return view('home', $data);
    }

    public function profile()
    {
        $user = auth()->user();

        return view('profile', compact('user'));
    }
}
