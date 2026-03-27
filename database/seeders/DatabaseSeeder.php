<?php

namespace Database\Seeders;

use App\Models\Auditorio;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Crear usuario admin
        User::create([
            'name' => 'Administrador',
            'email' => 'admin@admin.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'email_verified_at' => now(),
        ]);

        // Crear usuario normal
        User::create([
            'name' => 'Usuario Demo',
            'email' => 'usuario@demo.com',
            'password' => Hash::make('password'),
            'role' => 'usuario',
            'email_verified_at' => now(),
        ]);

        // Crear auditorios con asientos
        $auditorio1 = Auditorio::create([
            'nombre' => 'Auditorio Principal',
            'descripcion' => 'Auditorio principal con capacidad para eventos grandes.',
            'filas' => 8,
            'columnas' => 12,
        ]);
        $auditorio1->generarAsientos();

        $auditorio2 = Auditorio::create([
            'nombre' => 'Sala de Conferencias',
            'descripcion' => 'Sala pequeña para conferencias y presentaciones.',
            'filas' => 5,
            'columnas' => 8,
        ]);
        $auditorio2->generarAsientos();

        $auditorio3 = Auditorio::create([
            'nombre' => 'Teatro Municipal',
            'descripcion' => 'Teatro para eventos culturales y artísticos.',
            'filas' => 10,
            'columnas' => 15,
        ]);
        $auditorio3->generarAsientos();
    }
}
