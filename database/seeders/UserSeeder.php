<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role;

class UserSeeder extends Seeder
{
    public function run()
    {
        // Datos de las cajeras
        $cajeras = [
            ['name' => 'María Eugenia Girott', 'email' => 'maria-girott@ofummelli.com', 'password' => bcrypt('Vv13885083.*'), 'cedula' => '13885083'],
            ['name' => 'Karla Olivero', 'email' => 'karla-olivero@ofummelli.com', 'password' => bcrypt('Vv18815369.*'), 'cedula' => '18815369'],
            ['name' => 'Stefhani Montero', 'email' => 'stefhani-montero@ofummelli.com', 'password' => bcrypt('Vv22904609.*'), 'cedula' => '22904609'],
            ['name' => 'Andreina Berne', 'email' => 'andreina-berne@ofummelli.com', 'password' => bcrypt('Vv15316054.*'), 'cedula' => '15316054'],
        ];

        // Crear usuarios y asignarles el rol de Cajero
        foreach ($cajeras as $cajera) {
            $user = User::create([
                'name' => $cajera['name'],
                'email' => $cajera['email'],
                'password' => $cajera['password'],
            ]);
            $user->assignRole('Cajero');
        }
    }
}