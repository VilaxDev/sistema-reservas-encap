@extends('layouts.bootstrap')

@section('title', $auditorio->nombre . ' - Mapa de Asientos')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1><i class="bi bi-grid-3x3 me-2"></i>{{ $auditorio->nombre }}</h1>
        <div>
            <a href="{{ route('reservas.create', $auditorio) }}?fecha={{ $fecha }}" class="btn btn-success">
                <i class="bi bi-ticket-perforated me-1"></i>Reservar Asiento
            </a>
            <a href="{{ route('auditorios.publico') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left me-1"></i>Volver
            </a>
        </div>
    </div>

    <!-- Filtro de fecha -->
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" class="row g-3 align-items-end">
                <div class="col-md-3">
                    <label for="fecha" class="form-label">Fecha del Evento</label>
                    <input type="date" class="form-control" id="fecha" name="fecha" value="{{ $fecha }}">
                </div>
                <div class="col-md-3">
                    <button type="submit" class="btn btn-outline-primary">
                        <i class="bi bi-search me-1"></i>Ver Disponibilidad
                    </button>
                </div>
                <div class="col-md-6 text-end">
                    @php
                        $totalAsientos = $asientosPorFila->flatten()->count();
                        $ocupados = count($asientosReservados);
                        $disponibles = $totalAsientos - $ocupados;
                    @endphp
                    <span class="badge bg-success fs-6 me-2">Disponibles: {{ $disponibles }}</span>
                    <span class="badge bg-danger fs-6">Ocupados: {{ $ocupados }}</span>
                </div>
            </form>
        </div>
    </div>

    <!-- Mapa de Asientos -->
    <div class="card">
        <div class="card-body text-center">
            <div class="screen mb-4">ESCENARIO</div>

            <div class="seat-legend">
                <div class="item">
                    <div class="box" style="background:#d4edda;border-color:#28a745;"></div> Disponible
                </div>
                <div class="item">
                    <div class="box" style="background:#f8d7da;border-color:#dc3545;"></div> Ocupado
                </div>
            </div>

            @foreach($asientosPorFila as $fila => $asientos)
                <div class="d-flex align-items-center justify-content-center mb-1">
                    <strong class="me-3" style="width:30px;">{{ $fila }}</strong>
                    @foreach($asientos as $asiento)
                        @php
                            $ocupado = in_array($asiento->id, $asientosReservados);
                            $clase = $ocupado ? 'occupied' : 'available';
                            $titulo = $asiento->etiqueta;
                            if ($ocupado && Auth::user()->isAdmin() && isset($reservasInfo[$asiento->id])) {
                                $titulo .= ' - ' . $reservasInfo[$asiento->id]->user->name;
                            }
                        @endphp
                        <div class="seat {{ $clase }}" title="{{ $titulo }}">
                            {{ $asiento->numero }}
                        </div>
                    @endforeach
                </div>
            @endforeach
        </div>
    </div>
@endsection
