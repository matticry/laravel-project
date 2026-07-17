<?php

namespace Database\Factories;

use App\Models\Pet;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class PetFactory extends Factory
{
    protected $model = Pet::class;

    public function definition(): array
    {
        return [
            'name_pet' => $this->faker->firstName(),
            'species_pet' => $this->faker->randomElement(['Perro', 'Gato', 'Ave', 'Conejo', 'Hámster']),
            'breed_pet' => $this->faker->randomElement([
                'Labrador', 'Golden Retriever', 'Bulldog', 'Poodle',
                'Persa', 'Siamés', 'Maine Coon', 'Angora'
            ]),
            'age_pet' => $this->faker->numberBetween(1, 15),
            'image_pet' => null, // O un ID válido si tienes tabla de imágenes
            'status_pet' => 'A',
            'id_usu' => User::factory(), // Crea usuario si no existe
        ];
    }

    public function inactive()
    {
        return $this->state(function (array $attributes) {
            return [
                'status_pet' => 'I',
            ];
        });
    }
}
