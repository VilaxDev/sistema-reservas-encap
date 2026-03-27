@extends('layouts.bootstrap')

@section('title', 'Detalle de Reserva')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1><i class="bi bi-eye me-2"></i>Reserva #{{ $reserva->id }}</h1>
        <a href="{{ route('reservas.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-1"></i>Volver
        </a>
    </div>

    <div class="row">
        <div class="col-md-6 mb-3">
            <div class="card h-100">
                <div class="card-header"><h5 class="mb-0"><i class="bi bi-ticket-perforated me-1"></i>Datos de la Reserva</h5></div>
                <div class="card-body">
                    <table class="table table-borderless mb-0">
                        <tr>
                            <th class="text-muted" style="width:40%">Estado:</th>
                            <td>
                                <span class="badge bg-{{ $reserva->estado_badge }} fs-6">{{ ucfirst($reserva->estado) }}</span>
                            </td>
                        </tr>
                        <tr>
                            <th class="text-muted">Auditorio:</th>
                            <td><strong>{{ $reserva->asiento->auditorio->nombre }}</strong></td>
                        </tr>
                        <tr>
                            <th class="text-muted">Asiento:</th>
                            <td>
                                <span class="badge bg-secondary fs-6">{{ $reserva->asiento->etiqueta }}</span>
                                (Fila {{ $reserva->asiento->fila }}, Número {{ $reserva->asiento->numero }})
                            </td>
                        </tr>
                        <tr>
                            <th class="text-muted">Fecha del Evento:</th>
                            <td>{{ $reserva->fecha_evento->format('d/m/Y') }}</td>
                        </tr>
                        @if($reserva->notas)
                        <tr>
                            <th class="text-muted">Notas:</th>
                            <td>{{ $reserva->notas }}</td>
                        </tr>
                        @endif
                    </table>
                </div>
            </div>
        </div>

        <div class="col-md-6 mb-3">
            <div class="card h-100">
                <div class="card-header"><h5 class="mb-0"><i class="bi bi-person me-1"></i>Información</h5></div>
                <div class="card-body">
                    <table class="table table-borderless mb-0">
                        <tr>
                            <th class="text-muted" style="width:40%">Reservado por:</th>
                            <td>{{ $reserva->user->name }}</td>
                        </tr>
                        <tr>
                            <th class="text-muted">Email:</th>
                            <td>{{ $reserva->user->email }}</td>
                        </tr>
                        <tr>
                            <th class="text-muted">Fecha de Reserva:</th>
                            <td>{{ $reserva->created_at->format('d/m/Y H:i') }}</td>
                        </tr>
                        <tr>
                            <th class="text-muted">Última Actualización:</th>
                            <td>{{ $reserva->updated_at->format('d/m/Y H:i') }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>

    @if($reserva->estado === 'reservado')
        <div class="card mt-2">
            <div class="card-body d-flex gap-2">
                <form action="{{ route('reservas.cancelar', $reserva) }}" method="POST"
                      onsubmit="return confirm('¿Está seguro de cancelar esta reserva?')">
                    @csrf @method('PATCH')
                    <button type="submit" class="btn btn-danger">
                        <i class="bi bi-x-circle me-1"></i>Cancelar Reserva
                    </button>
                </form>
            </div>
        </div>
    @endif
@endsection
