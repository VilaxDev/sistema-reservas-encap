<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Auditorio extends Model
{
    protected $fillable = [
        'nombre',
        'descripcion',
        'filas',
        'columnas',
        'activo',
    ];

    protected $casts = [
        'activo' => 'boolean',
    ];

    public function asientos(): HasMany
    {
        return $this->hasMany(Asiento::class);
    }

    public function getCapacidadAttribute(): int
    {
        return $this->filas * $this->columnas;
    }

    /**
     * Genera los asientos del auditorio (filas A,B,C... x columnas 1,2,3...)
     */
    public function generarAsientos(): void
    {
        $this->asientos()->delete();

        for ($f = 0; $f < $this->filas; $f++) {
            $fila = chr(65 + $f); // A, B, C, D...
            for ($c = 1; $c <= $this->columnas; $c++) {
                $this->asientos()->create([
                    'fila' => $fila,
                    'numero' => $c,
                    'activo' => true,
                ]);
            }
        }
    }
}
