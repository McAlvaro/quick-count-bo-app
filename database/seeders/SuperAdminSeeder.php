<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class SuperAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Crear roles si no existen
        $superAdminRole = Role::firstOrCreate(['name' => 'super_admin']);
        $userRole       = Role::firstOrCreate(['name' => 'user']);

        // Crear super admin si no existe
        $email = 'root@yopmail.com';
        if (!User::where('email', $email)->exists()) {
            $superAdmin = User::create([
                'name'     => 'Super Admin',
                'email'    => $email,
                'password' => Hash::make('Master1*'), // Cambiar a algo seguro
            ]);

            // Asignar rol
            $superAdmin->assignRole($superAdminRole);

            $this->command->info("Super Admin creado: {$email} / password123");
        } else {
            $this->command->info("El Super Admin ya existe: {$email}");
        }
    }
}
