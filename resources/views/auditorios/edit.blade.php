@extends('layouts.bootstrap')

@section('title', 'Editar Auditorio')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1><i class="bi bi-pencil me-2"></i>Editar: {{ $auditorio->nombre }}</h1>
        <a href="{{ route('auditorios.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-1"></i>Volver
        </a>
    </div>

    <div class="card">
        <div class="card-body">
            <form action="{{ route('auditorios.update', $auditorio) }}" method="POST">
                @csrf @method('PUT')
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="nombre" class="form-label">Nombre <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('nombre') is-invalid @enderror"
                               id="nombre" name="nombre" value="{{ old('nombre', $auditorio->nombre) }}" required>
                        @error('nombre')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-3 mb-3">
                        <label for="filas" class="form-label">Filas <span class="text-danger">*</span></label>
                        <input type="number" class="form-control @error('filas') is-invalid @enderror"
                               id="filas" name="filas" value="{{ old('filas', $auditorio->filas) }}" min="1" max="26" required>
                        @error('filas')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-3 mb-3">
                        <label for="columnas" class="form-label">Columnas <span class="text-danger">*</span></label>
                        <input type="number" class="form-control @error('columnas') is-invalid @enderror"
                               id="columnas" name="columnas" value="{{ old('columnas', $auditorio->columnas) }}" min="1" max="50" required>
                        @error('columnas')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>
                <div class="mb-3">
                    <label for="descripcion" class="form-label">Descripción</label>
                    <textarea class="form-control @error('descripcion') is-invalid @enderror"
                              id="descripcion" name="descripcion" rows="3">{{ old('descripcion', $auditorio->descripcion) }}</textarea>
                    @error('descripcion')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="alert alert-warning">
                    <i class="bi bi-exclamation-triangle me-1"></i>
                    Si cambias las filas o columnas, los asientos se regenerarán y se perderán las reservas existentes.
                </div>
                <hr>
                <div class="d-flex justify-content-end gap-2">
                    <a href="{{ route('auditorios.index') }}" class="btn btn-secondary">Cancelar</a>
                    <button type="submit" class="btn btn-warning"><i class="bi bi-check-circle me-1"></i>Actualizar</button>
                </div>
            </form>
        </div>
    </div>
@endsection
