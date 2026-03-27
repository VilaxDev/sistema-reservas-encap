<?php

namespace App\Http\Controllers;

use App\Models\Auditorio;
use App\Models\Reserva;
use Illuminate\Http\Request;

class AuditorioController extends Controller
{
    public function index()
    {
        $auditorios = Auditorio::withCount('asientos')->orderBy('nombre')->paginate(10);
        return view('auditorios.index', compact('auditorios'));
    }

    public function create()
    {
        return view('auditorios.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string|max:1000',
            'filas' => 'required|integer|min:1|max:26',
            'columnas' => 'required|integer|min:1|max:50',
        ]);

        $auditorio = Auditorio::create($validated);
        $auditorio->generarAsientos();

        return redirect()->route('auditorios.index')
            ->with('success', "Auditorio creado con {$auditorio->capacidad} asientos generados.");
    }

    public function show(Request $request, Auditorio $auditorio)
    {
        $fecha = $request->get('fecha', now()->format('Y-m-d'));

        $asientos = $auditorio->asientos()->orderBy('fila')->orderBy('numero')->get();

        // Obtener IDs de asientos reservados para esta fecha
        $asientosReservados = Reserva::where('fecha_evento', $fecha)
            ->where('estado', 'reservado')
            ->whereIn('asiento_id', $asientos->pluck('id'))
            ->pluck('asiento_id')
            ->toArray();

        // Obtener info de quién reservó cada asiento (para admin)
        $reservasInfo = [];
        if (auth()->user()->isAdmin()) {
            $reservasInfo = Reserva::with('user')
                ->where('fecha_evento', $fecha)
                ->where('estado', 'reservado')
                ->whereIn('asiento_id', $asientos->pluck('id'))
                ->get()
                ->keyBy('asiento_id');
        }

        // Agrupar asientos por fila
        $asientosPorFila = $asientos->groupBy('fila');

        return view('auditorios.show', compact(
            'auditorio', 'asientosPorFila', 'asientosReservados', 'fecha', 'reservasInfo'
        ));
    }

    public function edit(Auditorio $auditorio)
    {
        return view('auditorios.edit', compact('auditorio'));
    }

    public function update(Request $request, Auditorio $auditorio)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string|max:1000',
            'filas' => 'required|integer|min:1|max:26',
            'columnas' => 'required|integer|min:1|max:50',
        ]);

        $regenerar = ($validated['filas'] != $auditorio->filas || $validated['columnas'] != $auditorio->columnas);

        $auditorio->update($validated);

        if ($regenerar) {
            $auditorio->generarAsientos();
            return redirect()->route('auditorios.index')
                ->with('success', 'Auditorio actualizado y asientos regenerados.');
        }

        return redirect()->route('auditorios.index')
            ->with('success', 'Auditorio actualizado exitosamente.');
    }

    public function destroy(Auditorio $auditorio)
    {
        $auditorio->delete();

        return redirect()->route('auditorios.index')
            ->with('success', 'Auditorio eliminado exitosamente.');
    }
}
