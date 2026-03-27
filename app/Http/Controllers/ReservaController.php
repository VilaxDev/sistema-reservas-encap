<?php

namespace App\Http\Controllers;

use App\Models\Asiento;
use App\Models\Auditorio;
use App\Models\Reserva;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReservaController extends Controller
{
    public function index(Request $request)
    {
        $query = Reserva::with(['user', 'asiento.auditorio'])
            ->orderBy('created_at', 'desc');

        // Si no es admin, solo ver sus propias reservas
        if (!Auth::user()->isAdmin()) {
            $query->where('user_id', Auth::id());
        }

        if ($request->filled('estado')) {
            $query->where('estado', $request->estado);
        }

        if ($request->filled('fecha')) {
            $query->whereDate('fecha_evento', $request->fecha);
        }

        $reservas = $query->paginate(10)->withQueryString();

        return view('reservas.index', compact('reservas'));
    }

    /**
     * Muestra el mapa de asientos para hacer una reserva.
     */
    public function create(Request $request, Auditorio $auditorio)
    {
        $fecha = $request->get('fecha', now()->format('Y-m-d'));

        $asientos = $auditorio->asientos()->orderBy('fila')->orderBy('numero')->get();

        $asientosReservados = Reserva::where('fecha_evento', $fecha)
            ->where('estado', 'reservado')
            ->whereIn('asiento_id', $asientos->pluck('id'))
            ->pluck('asiento_id')
            ->toArray();

        $asientosPorFila = $asientos->groupBy('fila');

        return view('reservas.create', compact(
            'auditorio', 'asientosPorFila', 'asientosReservados', 'fecha'
        ));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'asiento_id' => 'required|exists:asientos,id',
            'fecha_evento' => 'required|date|after_or_equal:today',
            'notas' => 'nullable|string|max:500',
        ]);

        $asiento = Asiento::findOrFail($validated['asiento_id']);

        // Validación: asiento no esté ya reservado para esa fecha
        if ($asiento->estaReservado($validated['fecha_evento'])) {
            return back()->with('error', 'Este asiento ya está reservado para la fecha seleccionada.');
        }

        // Validación: usuario no tenga ya una reserva activa para este auditorio en esta fecha
        $reservaExistente = Reserva::where('user_id', Auth::id())
            ->where('fecha_evento', $validated['fecha_evento'])
            ->where('estado', 'reservado')
            ->whereHas('asiento', function ($q) use ($asiento) {
                $q->where('auditorio_id', $asiento->auditorio_id);
            })
            ->exists();

        if ($reservaExistente) {
            return back()->with('error', 'Ya tienes una reserva activa en este auditorio para esta fecha.');
        }

        Reserva::create([
            'user_id' => Auth::id(),
            'asiento_id' => $validated['asiento_id'],
            'fecha_evento' => $validated['fecha_evento'],
            'estado' => 'reservado',
            'notas' => $validated['notas'] ?? null,
        ]);

        return redirect()->route('reservas.index')
            ->with('success', "Asiento {$asiento->etiqueta} reservado exitosamente.");
    }

    public function show(Reserva $reserva)
    {
        // Solo admin o el dueño de la reserva puede verla
        if (!Auth::user()->isAdmin() && $reserva->user_id !== Auth::id()) {
            abort(403);
        }

        $reserva->load(['user', 'asiento.auditorio']);

        return view('reservas.show', compact('reserva'));
    }

    /**
     * Cancelar una reserva.
     */
    public function cancelar(Reserva $reserva)
    {
        if (!Auth::user()->isAdmin() && $reserva->user_id !== Auth::id()) {
            abort(403);
        }

        if ($reserva->estado === 'cancelado') {
            return back()->with('error', 'Esta reserva ya fue cancelada.');
        }

        $reserva->update(['estado' => 'cancelado']);

        return redirect()->route('reservas.index')
            ->with('success', 'Reserva cancelada exitosamente.');
    }
}
