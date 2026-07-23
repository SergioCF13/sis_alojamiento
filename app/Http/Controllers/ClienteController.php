<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class ClienteController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware(function ($request, $next) {
            $user = auth()->user();
            if ($user && $user->role === 'Limpieza') {
                abort(403);
            }

            if ($user && $user->role === 'Recepcionista' && in_array($request->route()->getActionMethod(), ['destroy'])) {
                abort(403);
            }

            return $next($request);
        });
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $clientes = Cliente::select(['id', 'nombre_completo', 'carnet_identidad', 'celular']);

            return DataTables::of($clientes)
                ->editColumn('celular', function ($cliente) {
                    return $cliente->celular 
                        ? '<i class="fas fa-phone-alt text-muted mr-1"></i>' . e($cliente->celular) 
                        : '<span class="text-muted">-</span>';
                })
                ->editColumn('carnet_identidad', function ($cliente) {
                    return '<span class="badge badge-light border">' . e($cliente->carnet_identidad) . '</span>';
                })
                ->addColumn('acciones', function ($cliente) {
                    $btnVer = '<a href="' . route('clientes.show', $cliente) . '" class="btn btn-info btn-sm" title="Ver"><i class="fas fa-eye"></i></a>';

                    $buttons = $btnVer;

                    if (auth()->user() && auth()->user()->role === 'Administrador') {
                        $btnEditar = '<a href="' . route('clientes.edit', $cliente) . '" class="btn btn-warning btn-sm" title="Editar"><i class="fas fa-pencil-alt"></i></a>';
                        $btnEliminar = '<button type="button" class="btn btn-danger btn-sm" onclick="confirmarEliminar(' . $cliente->id . ', \'' . e($cliente->nombre_completo) . '\')" title="Eliminar"><i class="fas fa-trash"></i></button>';
                        $buttons .= ' ' . $btnEditar . ' ' . $btnEliminar;
                    }

                    return '<div class="btn-group btn-group-sm">' . $buttons . '</div>';
                })
                ->rawColumns(['carnet_identidad', 'celular', 'acciones'])
                ->make(true);
        }

        return view('clientes.index');
    }

    public function create()
    {
        return view('clientes.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nombre_completo'  => 'required|string|max:255',
            'carnet_identidad' => 'required|string|max:50|unique:clientes,carnet_identidad',
            'celular'          => 'nullable|string|max:25',
        ]);

        Cliente::create($data);

        return redirect()->route('clientes.index')
            ->with('success', 'Cliente creado correctamente.');
    }

    public function show(Cliente $cliente)
    {
        return view('clientes.show', compact('cliente'));
    }

    public function edit(Cliente $cliente)
    {
        return view('clientes.edit', compact('cliente'));
    }

    public function update(Request $request, Cliente $cliente)
    {
        $data = $request->validate([
            'nombre_completo'  => 'required|string|max:255',
            'carnet_identidad' => 'required|string|max:50|unique:clientes,carnet_identidad,' . $cliente->id,
            'celular'          => 'nullable|string|max:25',
        ]);

        $cliente->update($data);

        return redirect()->route('clientes.index')
            ->with('success', 'Cliente actualizado correctamente.');
    }

    public function destroy(Cliente $cliente)
    {
        $cliente->delete();

        return response()->json(['success' => true, 'message' => 'Cliente eliminado correctamente.']);
    }
}