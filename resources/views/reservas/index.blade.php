@extends('layouts.bootstrap')

@section('title', 'Mis Reservas')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1><i class="bi bi-ticket-perforated me-2"></i>{{ Auth::user()->isAdmin() ? 'Todas las Reservas' : 'Mis Reservas' }}</h1>
        <a href="{{ route('auditorios.publico') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle me-1"></i>Nueva Reserva
        </a>
    </div>

    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('reservas.index') }}" class="row g-3 align-items-end">
                <div class="col-md-3">
                    <label for="estado" class="form-label">Estado</label>
                    <select class="form-select" id="estado" name="estado">
                        <option value="">Todos</option>
                        <option value="reservado" {{ request('estado') == 'reservado' ? 'selected' : '' }}>Reservado</option>
                        <option value="cancelado" {{ request('estado') == 'cancelado' ? 'selected' : '' }}>Cancelado</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="fecha" class="form-label">Fecha</label>
                    <input type="date" class="form-control" id="fecha" name="fecha" value="{{ request('fecha') }}">
                </div>
                <div class="col-md-3">
                    <button type="submit" class="btn btn-outline-primary me-2">
                        <i class="bi bi-search me-1"></i>Filtrar
                    </button>
                    <a href="{{ route('reservas.index') }}" class="btn btn-outline-secondary">Limpiar</a>
                </div>
            </form>
        </div>
    </div>

    <div class="card">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-dark">
                    <tr>
                        <th>#</th>
                        @if(Auth::user()->isAdmin())<th>Usuario</th>@endif
                        <th>Auditorio</th>
                        <th>Asiento</th>
                        <th>Fecha Evento</th>
                        <th>Estado</th>
                        <th>Creada</th>
                        <th class="text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($reservas as $reserva)
                        <tr>
                            <td>{{ $reserva->id }}</td>
                            @if(Auth::user()->isAdmin())
                                <td>{{ $reserva->user->name ?? 'N/A' }}</td>
                            @endif
                            <td><strong>{{ $reserva->asiento->auditorio->nombre ?? 'N/A' }}</strong></td>
                            <td>
                                <span class="badge bg-secondary">{{ $reserva->asiento->etiqueta ?? 'N/A' }}</span>
                            </td>
                            <td>{{ $reserva->fecha_evento->format('d/m/Y') }}</td>
                            <td>
                                <span class="badge bg-{{ $reserva->estado_badge }}">
                                    {{ ucfirst($reserva->estado) }}
                                </span>
                            </td>
                            <td>{{ $reserva->created_at->format('d/m/Y H:i') }}</td>
                            <td class="text-center">
                                <div class="btn-group btn-group-sm">
                                    <a href="{{ route('reservas.show', $reserva) }}" class="btn btn-outline-info" title="Ver">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    @if($reserva->estado === 'reservado')
                                        <form action="{{ route('reservas.cancelar', $reserva) }}" method="POST"
                                              style="display:inline;" onsubmit="return confirm('¿Cancelar esta reserva?')">
                                            @csrf @method('PATCH')
                                            <button type="submit" class="btn btn-outline-danger btn-sm" title="Cancelar">
                                                <i class="bi bi-x-circle"></i>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="{{ Auth::user()->isAdmin() ? 8 : 7 }}" class="text-center py-4 text-muted">
                                <i class="bi bi-inbox fs-1 d-block mb-2"></i>
                                No hay reservas.
                                <br><a href="{{ route('auditorios.publico') }}" class="btn btn-primary btn-sm mt-2">Hacer una reserva</a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($reservas->hasPages())
            <div class="card-footer">{{ $reservas->links() }}</div>
        @endif
    </div>
@endsection
