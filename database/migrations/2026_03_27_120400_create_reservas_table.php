<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reservas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('asiento_id')->constrained()->cascadeOnDelete();
            $table->date('fecha_evento');
            $table->enum('estado', ['reservado', 'cancelado'])->default('reservado');
            $table->text('notas')->nullable();
            $table->timestamps();

            $table->unique(['asiento_id', 'fecha_evento', 'estado']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reservas');
    }
};
