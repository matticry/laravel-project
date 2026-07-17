<?php

namespace Database\Seeders;

use App\Models\Pet;
use App\Models\User;
use Illuminate\Database\Seeder;

class PetSeeder extends Seeder
{
    public function run(): void
    {
        // Obtener usuarios existentes
        $users = User::take(5)->get();

        if ($users->count() > 0) {
            foreach ($users as $user) {
                // Crear 1-3 mascotas por usuario
                Pet::factory()
                    ->count(rand(1, 3))
                    ->create(['id_usu' => $user->us_id]);
            }
        } else {
            // Si no hay usuarios, crear algunos con mascotas
            User::factory()
                ->count(3)
                ->has(Pet::factory()->count(2), 'pets')
                ->create();
        }
    }
}
