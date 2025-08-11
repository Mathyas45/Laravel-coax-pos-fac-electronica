<?php

namespace Database\Seeders;

use App\Models\Client;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class ClientSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Cliente normal ejemplo
        Client::create([
            'name' => 'Juan Carlos',
            'surname' => 'Pérez García',
            'full_name' => 'Juan Carlos Pérez García',
            'phone' => '987654321',
            'email' => 'juan.perez@email.com',
            'type_client' => 1,
            'type_document' => 'DNI',
            'n_document' => '12345678',
            'gender' => 'M',
            'birth_date' => '1990-05-15',
            'address' => 'Av. Los Olivos 123, San Martín de Porres',
            'distrito' => 'San Martín de Porres',
            'provincia' => 'Lima',
            'region' => 'Lima',
            'state' => 1,
        ]);

        // Cliente empresa ejemplo
        Client::create([
            'full_name' => 'Empresa Distribuidora SAC',
            'phone' => '014567890',
            'email' => 'contacto@distribuidora.com',
            'type_client' => 2,
            'type_document' => 'RUC',
            'n_document' => '20123456789',
            'address' => 'Jr. Comercio 456, Cercado de Lima',
            'distrito' => 'Cercado de Lima',
            'provincia' => 'Lima',
            'region' => 'Lima',
            'state' => 1,
        ]);

        // Más clientes de ejemplo
        Client::create([
            'name' => 'María Elena',
            'surname' => 'Rodríguez López',
            'full_name' => 'María Elena Rodríguez López',
            'phone' => '965478123',
            'email' => 'maria.rodriguez@email.com',
            'type_client' => 1,
            'type_document' => 'DNI',
            'n_document' => '87654321',
            'gender' => 'F',
            'birth_date' => '1985-08-22',
            'address' => 'Calle Las Flores 789, Miraflores',
            'distrito' => 'Miraflores',
            'provincia' => 'Lima',
            'region' => 'Lima',
            'state' => 1,
        ]);
    }
}
