<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Reserva extends Model
{
    protected $fillable = [
        'user_id',
        'asiento_id',
        'fecha_evento',
        'estado',
        'notas',
    ];

    protected $casts = [
        'fecha_evento' => 'date',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function asiento(): BelongsTo
    {
        return $this->belongsTo(Asiento::class);
    }

    public function getEstadoBadgeAttribute(): string
    {
        return match ($this->estado) {
            'reservado' => 'success',
            'cancelado' => 'danger',
            default => 'secondary',
        };
    }

    /**
     * Scope: solo reservas activas (no canceladas)
     */
    public function scopeActivas($query)
    {
        return $query->where('estado', 'reservado');
    }
}
