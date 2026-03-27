<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Asiento extends Model
{
    protected $fillable = [
        'auditorio_id',
        'fila',
        'numero',
        'activo',
    ];

    protected $casts = [
        'activo' => 'boolean',
    ];

    public function auditorio(): BelongsTo
    {
        return $this->belongsTo(Auditorio::class);
    }

    public function reservas(): HasMany
    {
        return $this->hasMany(Reserva::class);
    }

    /**
     * Verifica si el asiento está reservado para una fecha específica.
     */
    public function estaReservado(string $fecha): bool
    {
        return $this->reservas()
            ->where('fecha_evento', $fecha)
            ->where('estado', 'reservado')
            ->exists();
    }

    /**
     * Etiqueta del asiento: ej. "A-1", "B-5"
     */
    public function getEtiquetaAttribute(): string
    {
        return $this->fila . '-' . $this->numero;
    }
}
