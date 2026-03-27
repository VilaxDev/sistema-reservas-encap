@extends('layouts.bootstrap')

@section('title', 'Nuevo Auditorio')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1><i class="bi bi-plus-circle me-2"></i>Nuevo Auditorio</h1>
        <a href="{{ route('auditorios.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-1"></i>Volver
        </a>
    </div>

    <div class="card">
        <div class="card-body">
            <form action="{{ route('auditorios.store') }}" method="POST">
                @csrf
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="nombre" class="form-label">Nombre <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('nombre') is-invalid @enderror"
                               id="nombre" name="nombre" value="{{ old('nombre') }}" required placeholder="Ej: Auditorio Principal">
                        @error('nombre')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-3 mb-3">
                        <label for="filas" class="form-label">Filas <span class="text-danger">*</span></label>
                        <input type="number" class="form-control @error('filas') is-invalid @enderror"
                               id="filas" name="filas" value="{{ old('filas', 5) }}" min="1" max="26" required>
                        @error('filas')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        <small class="text-muted">A-Z (máx 26)</small>
                    </div>
                    <div class="col-md-3 mb-3">
                        <label for="columnas" class="form-label">Columnas <span class="text-danger">*</span></label>
                        <input type="number" class="form-control @error('columnas') is-invalid @enderror"
                               id="columnas" name="columnas" value="{{ old('columnas', 10) }}" min="1" max="50" required>
                        @error('columnas')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>
                <div class="mb-3">
                    <label for="descripcion" class="form-label">Descripción</label>
                    <textarea class="form-control @error('descripcion') is-invalid @enderror"
                              id="descripcion" name="descripcion" rows="3">{{ old('descripcion') }}</textarea>
                    @error('descripcion')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="alert alert-info">
                    <i class="bi bi-info-circle me-1"></i>
                    Los asientos se generan automáticamente al crear el auditorio (Filas × Columnas).
                </div>
                <hr>
                <div class="d-flex justify-content-end gap-2">
                    <a href="{{ route('auditorios.index') }}" class="btn btn-secondary">Cancelar</a>
                    <button type="submit" class="btn btn-primary"><i class="bi bi-check-circle me-1"></i>Crear Auditorio</button>
                </div>
            </form>
        </div>
    </div>
@endsection
