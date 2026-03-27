@extends('layouts.bootstrap')

@section('title', 'Auditorios')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1><i class="bi bi-building me-2"></i>Auditorios</h1>
        @if(Auth::user()->isAdmin())
            <a href="{{ route('auditorios.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-circle me-1"></i>Nuevo Auditorio
            </a>
        @endif
    </div>

    <div class="row">
        @forelse($auditorios as $auditorio)
            <div class="col-md-4 mb-4">
                <div class="card h-100">
                    <div class="card-body">
                        <h5 class="card-title">
                            <i class="bi bi-building me-1"></i>{{ $auditorio->nombre }}
                        </h5>
                        @if($auditorio->descripcion)
                            <p class="card-text text-muted">{{ Str::limit($auditorio->descripcion, 100) }}</p>
                        @endif
                        <ul class="list-unstyled mb-0">
                            <li><i class="bi bi-grid-3x3 me-1"></i>Filas: {{ $auditorio->filas }} | Columnas: {{ $auditorio->columnas }}</li>
                            <li><i class="bi bi-people me-1"></i>Capacidad: {{ $auditorio->capacidad }} asientos</li>
                            <li>
                                <i class="bi bi-circle-fill me-1 {{ $auditorio->activo ? 'text-success' : 'text-danger' }}"></i>
                                {{ $auditorio->activo ? 'Activo' : 'Inactivo' }}
                            </li>
                        </ul>
                    </div>
                    <div class="card-footer d-flex gap-2">
                        <a href="{{ Auth::user()->isAdmin() ? route('auditorios.show', $auditorio) : route('auditorios.ver', $auditorio) }}"
                           class="btn btn-sm btn-outline-primary">
                            <i class="bi bi-eye me-1"></i>Ver Asientos
                        </a>
                        <a href="{{ route('reservas.create', $auditorio) }}" class="btn btn-sm btn-success">
                            <i class="bi bi-ticket-perforated me-1"></i>Reservar
                        </a>
                        @if(Auth::user()->isAdmin())
                            <a href="{{ route('auditorios.edit', $auditorio) }}" class="btn btn-sm btn-outline-warning">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <form action="{{ route('auditorios.destroy', $auditorio) }}" method="POST"
                                  onsubmit="return confirm('¿Eliminar este auditorio y todos sus asientos?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="text-center py-5 text-muted">
                    <i class="bi bi-building fs-1 d-block mb-2"></i>
                    No hay auditorios registrados.
                </div>
            </div>
        @endforelse
    </div>

    @if($auditorios->hasPages())
        <div class="mt-3">{{ $auditorios->links() }}</div>
    @endif
@endsection
