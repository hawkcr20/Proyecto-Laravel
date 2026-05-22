<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

use App\Models\Usuario;
use App\Models\Rol;
use App\Models\Clase;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {

        $userRole = Rol::firstOrCreate([
            'nombre' => 'ROLE_USER'
        ]);

        $adminRole = Rol::firstOrCreate([
            'nombre' => 'ROLE_ADMIN'
        ]);

        Usuario::firstOrCreate(

            ['userName' => 'usuario'],

            [
                'nombre' => 'Juan',
                'apellidoUno' => 'Perez',
                'apellidoDos' => 'Lopez',
                'email' => 'usuario@gmail.com',
                'telefono' => '88888888',
                'password' => Hash::make('1234'),

                // CORREGIDO
                'idRol' => $userRole->id,
            ]
        );

        Usuario::firstOrCreate(

            ['userName' => 'admin'],

            [
                'nombre' => 'Admin',
                'apellidoUno' => 'Principal',
                'apellidoDos' => 'Sistema',
                'email' => 'admin@gmail.com',
                'telefono' => '99999999',
                'password' => Hash::make('1234'),

                // CORREGIDO
                'idRol' => $adminRole->id,
            ]
        );

        Clase::firstOrCreate(

            ['nombre' => 'Yoga'],

            [
                'descripcion' => 'Clase de yoga avanzada',
                'diaSemana' => 'Lunes',
                'horario' => '18:00:00',
                'capacidad' => 20,
            ]
        );

        Clase::firstOrCreate(

            ['nombre' => 'Crossfit'],

            [
                'descripcion' => 'Entrenamiento de alta intensidad',
                'diaSemana' => 'Martes',
                'horario' => '19:00:00',
                'capacidad' => 15,
            ]
        );
    }
}
