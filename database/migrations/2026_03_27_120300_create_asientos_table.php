<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('asientos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('auditorio_id')->constrained()->cascadeOnDelete();
            $table->string('fila', 5);
            $table->integer('numero');
            $table->boolean('activo')->default(true);
            $table->timestamps();

            $table->unique(['auditorio_id', 'fila', 'numero']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('asientos');
    }
};
