@extends('layouts.bootstrap')

@section('title', 'Dashboard')

@section('content')
    <h1 class="mb-4"><i class="bi bi-speedometer2 me-2"></i>Dashboard</h1>

    <div class="row mb-4">
        @if(Auth::user()->isAdmin())
        <div class="col-md-3 mb-3">
            <div class="card text-white bg-primary">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title mb-0">Usuarios</h6>
                            <h2 class="mb-0 mt-2">{{ $totalUsuarios }}</h2>
                        </div>
                        <i class="bi bi-people fs-1 opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
        @endif
        <div class="col-md-3 mb-3">
            <div class="card text-white bg-success">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title mb-0">Auditorios</h6>
                            <h2 class="mb-0 mt-2">{{ $totalAuditorios }}</h2>
                        </div>
                        <i class="bi bi-building fs-1 opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card text-white bg-warning">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title mb-0">Reservas Activas</h6>
                            <h2 class="mb-0 mt-2">{{ $totalReservas }}</h2>
                        </div>
                        <i class="bi bi-ticket-perforated fs-1 opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card text-white bg-info">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title mb-0">Reservas Hoy</h6>
                            <h2 class="mb-0 mt-2">{{ $reservasHoy }}</h2>
                        </div>
                        <i class="bi bi-calendar-day fs-1 opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6 mb-3">
            <div class="card">
                <div class="card-header"><h5 class="mb-0"><i class="bi bi-lightning me-2"></i>Acciones Rápidas</h5></div>
                <div class="card-body">
                    <a href="{{ route('auditorios.publico') }}" class="btn btn-primary me-2 mb-2">
                        <i class="bi bi-building me-1"></i>Ver Auditorios
                    </a>
                    <a href="{{ route('reservas.index') }}" class="btn btn-outline-primary me-2 mb-2">
                        <i class="bi bi-list-ul me-1"></i>Mis Reservas
                    </a>
                    @if(Auth::user()->isAdmin())
                        <a href="{{ route('usuarios.index') }}" class="btn btn-outline-dark mb-2">
                            <i class="bi bi-people me-1"></i>Gestionar Usuarios
                        </a>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-md-6 mb-3">
            <div class="card">
                <div class="card-header"><h5 class="mb-0"><i class="bi bi-ticket-perforated me-2"></i>Mis Próximas Reservas</h5></div>
                <div class="card-body p-0">
                    @if($misReservas->count() > 0)
                        <div class="list-group list-group-flush">
                            @foreach($misReservas as $reserva)
                                <a href="{{ route('reservas.show', $reserva) }}" class="list-group-item list-group-item-action">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <strong>{{ $reserva->asiento->auditorio->nombre }}</strong>
                                            <br>
                                            <small class="text-muted">
                                                Asiento {{ $reserva->asiento->etiqueta }}
                                                · {{ $reserva->fecha_evento->format('d/m/Y') }}
                                            </small>
                                        </div>
                                        <span class="badge bg-{{ $reserva->estado_badge }}">{{ ucfirst($reserva->estado) }}</span>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    @else
                        <p class="text-muted text-center py-3 mb-0">No tienes reservas próximas.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
