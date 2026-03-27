@extends('layouts.bootstrap')

@section('title', 'Reservar Asiento - ' . $auditorio->nombre)

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1><i class="bi bi-ticket-perforated me-2"></i>Reservar en: {{ $auditorio->nombre }}</h1>
        <a href="{{ route('auditorios.publico') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-1"></i>Volver
        </a>
    </div>

    <!-- Selector de fecha -->
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('reservas.create', $auditorio) }}" class="row g-3 align-items-end">
                <div class="col-md-3">
                    <label for="fecha" class="form-label">Fecha del Evento</label>
                    <input type="date" class="form-control" id="fecha" name="fecha"
                           value="{{ $fecha }}" min="{{ now()->format('Y-m-d') }}">
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
                    <span class="badge bg-danger fs-6 me-2">Ocupados: {{ $ocupados }}</span>
                    <span class="badge bg-primary fs-6">Total: {{ $totalAsientos }}</span>
                </div>
            </form>
        </div>
    </div>

    @if($disponibles === 0)
        <div class="alert alert-warning text-center">
            <i class="bi bi-exclamation-triangle fs-4 d-block mb-2"></i>
            <strong>No hay asientos disponibles para esta fecha.</strong>
            <br>Prueba con otra fecha.
        </div>
    @else
        <!-- Mapa de asientos + Formulario -->
        <form action="{{ route('reservas.store') }}" method="POST" id="formReserva">
            @csrf
            <input type="hidden" name="asiento_id" id="asiento_id" value="">
            <input type="hidden" name="fecha_evento" value="{{ $fecha }}">

            <div class="card mb-3">
                <div class="card-body text-center">
                    <div class="screen">ESCENARIO</div>

                    <div class="seat-legend">
                        <div class="item">
                            <div class="box" style="background:#d4edda;border-color:#28a745;"></div> Disponible
                        </div>
                        <div class="item">
                            <div class="box" style="background:#f8d7da;border-color:#dc3545;"></div> Ocupado
                        </div>
                        <div class="item">
                            <div class="box" style="background:#007bff;border-color:#0056b3;"></div> Seleccionado
                        </div>
                    </div>

                    @foreach($asientosPorFila as $fila => $asientos)
                        <div class="d-flex align-items-center justify-content-center mb-1">
                            <strong class="me-3" style="width:30px;">{{ $fila }}</strong>
                            @foreach($asientos as $asiento)
                                @php
                                    $ocupado = in_array($asiento->id, $asientosReservados);
                                @endphp
                                <div class="seat {{ $ocupado ? 'occupied' : 'available' }}"
                                     data-id="{{ $asiento->id }}"
                                     data-etiqueta="{{ $asiento->etiqueta }}"
                                     @if(!$ocupado) onclick="seleccionarAsiento(this)" @endif
                                     title="{{ $asiento->etiqueta }}{{ $ocupado ? ' (Ocupado)' : '' }}">
                                    {{ $asiento->numero }}
                                </div>
                            @endforeach
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Panel de confirmación -->
            <div class="card" id="panelConfirmacion" style="display:none;">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-md-4">
                            <h5 class="mb-0">
                                <i class="bi bi-check2-square me-1"></i>
                                Asiento seleccionado: <strong id="asientoSeleccionado" class="text-primary">-</strong>
                            </h5>
                        </div>
                        <div class="col-md-4">
                            <label for="notas" class="form-label mb-1">Notas (opcional)</label>
                            <input type="text" class="form-control" id="notas" name="notas" placeholder="Alguna nota...">
                        </div>
                        <div class="col-md-4 text-end">
                            <button type="submit" class="btn btn-success btn-lg">
                                <i class="bi bi-check-circle me-1"></i>Confirmar Reserva
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    @endif
@endsection

@push('scripts')
<script>
    let asientoActual = null;

    function seleccionarAsiento(el) {
        // Deseleccionar anterior
        if (asientoActual) {
            asientoActual.classList.remove('selected');
            asientoActual.classList.add('available');
        }

        // Seleccionar nuevo
        el.classList.remove('available');
        el.classList.add('selected');
        asientoActual = el;

        // Actualizar formulario
        document.getElementById('asiento_id').value = el.dataset.id;
        document.getElementById('asientoSeleccionado').textContent = el.dataset.etiqueta;
        document.getElementById('panelConfirmacion').style.display = 'block';

        // Scroll suave al panel
        document.getElementById('panelConfirmacion').scrollIntoView({ behavior: 'smooth', block: 'center' });
    }

    // Validar que se seleccionó un asiento
    document.getElementById('formReserva')?.addEventListener('submit', function(e) {
        if (!document.getElementById('asiento_id').value) {
            e.preventDefault();
            alert('Por favor selecciona un asiento.');
        }
    });
</script>
@endpush
