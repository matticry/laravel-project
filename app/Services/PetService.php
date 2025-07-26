<?php

namespace App\Services;

use App\Contracts\PetRepositoryInterface;
use App\Models\Pet;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;

class PetService
{
    protected PetRepositoryInterface $petRepository;

    public function __construct(PetRepositoryInterface $petRepository)
    {
        $this->petRepository = $petRepository;
    }

    /**
     * Obtener todas las mascotas con paginación
     */
    public function getAllPets(int $perPage = 15, array $filters = []): LengthAwarePaginator
    {
        return $this->petRepository->getAllPaginated($perPage, $filters);
    }

    /**
     * Obtener mascota por ID
     */
    public function getPetById(int $id): ?Pet
    {
        return $this->petRepository->findByIdWithOwner($id);
    }

    /**
     * Crear nueva mascota con integración API externa
     */
    public function createPet(array $data): Pet
    {
        // Validar datos básicos
        $this->validatePetData($data);

        // Enriquecer datos con API externa
        $enrichedData = $this->enrichPetDataWithExternalApi($data);

        // Crear mascota
        return $this->petRepository->create($enrichedData);
    }

    /**
     * Actualizar mascota
     */
    public function updatePet(int $id, array $data): ?Pet
    {
        $existingPet = $this->petRepository->findById($id);
        if (!$existingPet) {
            throw new \Exception("Mascota no encontrada");
        }

        // Si se cambió la especie o raza, actualizar datos externos
        if (isset($data['species_pet']) || isset($data['breed_pet'])) {
            $data = $this->enrichPetDataWithExternalApi(array_merge(
                $existingPet->toArray(),
                $data
            ));
        }

        return $this->petRepository->update($id, $data);
    }

    /**
     * Eliminar mascota
     */
    public function deletePet(int $id): bool
    {
        $pet = $this->petRepository->findById($id);
        if (!$pet) {
            throw new \Exception("Mascota no encontrada");
        }

        return $this->petRepository->delete($id);
    }

    /**
     * Activar mascota
     */
    public function activatePet(int $id): bool
    {
        return $this->petRepository->changeStatus($id, 'A');
    }

    /**
     * Desactivar mascota
     */
    public function deactivatePet(int $id): bool
    {
        return $this->petRepository->changeStatus($id, 'I');
    }

    /**
     * Obtener mascotas de un usuario
     */
    public function getUserPets(int $userId, bool $activeOnly = false): Collection
    {
        if ($activeOnly) {
            return $this->petRepository->getActiveByUser($userId);
        }

        return $this->petRepository->getByUser($userId);
    }

    /**
     * Buscar mascotas por criterio
     */
    public function searchPets(array $criteria): Collection
    {
        $filters = [];

        if (!empty($criteria['name'])) {
            return $this->petRepository->searchByName($criteria['name']);
        }

        if (!empty($criteria['species'])) {
            return $this->petRepository->getBySpecies($criteria['species']);
        }

        return $this->petRepository->getAll($criteria);
    }

    /**
     * Obtener estadísticas de mascotas
     */
    public function getPetStatistics(): array
    {
        return $this->petRepository->getStatistics();
    }

    /**
     * Obtener mascotas con sus dueños
     */
    public function getPetsWithOwners(int $perPage = 15): LengthAwarePaginator
    {
        return $this->petRepository->getAllPaginated($perPage, ['status' => 'A']);
    }

    /**
     * Validar datos de mascota
     */
    private function validatePetData(array $data): void
    {
        $requiredFields = ['name_pet', 'species_pet', 'breed_pet', 'age_pet', 'id_usu'];

        foreach ($requiredFields as $field) {
            if (empty($data[$field])) {
                throw new \InvalidArgumentException("Campo requerido faltante: {$field}");
            }
        }

        if ($data['age_pet'] < 0 || $data['age_pet'] > 30) {
            throw new \InvalidArgumentException("Edad inválida");
        }
    }

    /**
     * Enriquecer datos con API externa
     */
    private function enrichPetDataWithExternalApi(array $data): array
    {
        try {
            $species = strtolower($data['species_pet']);
            $breed = $data['breed_pet'];

            // Integración con TheDogAPI o TheCatAPI
            $externalData = $this->fetchBreedInfo($species, $breed);
            $imageUrl = $this->fetchRandomImage($species);

            // Agregar datos externos si están disponibles
            if ($externalData) {
                $data['external_data'] = json_encode($externalData);
            }

            if ($imageUrl) {
                $data['external_image_url'] = $imageUrl;
            }

            return $data;

        } catch (\Exception $e) {
            Log::warning("Error enriching pet data with external API", [
                'species' => $species ?? null,
                'breed' => $breed ?? null,
                'error' => $e->getMessage()
            ]);

            // Retornar datos originales si falla la API externa
            return $data;
        }
    }

    /**
     * Obtener información de raza desde API externa
     */
    private function fetchBreedInfo(string $species, string $breed): ?array
    {
        try {
            $apiKey = 'live_mzJ4KTuSnd83BKHeu0598bGHOWVMie5gViPeMlOwU3ev2Df109kB7MXu81u6vnOo';

            if (strtolower($species) === 'perro' || strtolower($species) === 'dog') {
                $response = Http::timeout(10)
                    ->withHeaders(['x-api-key' => $apiKey])
                    ->get("https://api.thedogapi.com/v1/breeds/search", [
                        'q' => $breed
                    ]);
            } elseif (strtolower($species) === 'gato' || strtolower($species) === 'cat') {
                $response = Http::timeout(10)
                    ->withHeaders(['x-api-key' => $apiKey])
                    ->get("https://api.thecatapi.com/v1/breeds/search", [
                        'q' => $breed
                    ]);
            } else {
                return null;
            }

            if ($response->successful() && !empty($response->json())) {
                return $response->json()[0] ?? null;
            }

            return null;

        } catch (\Exception $e) {
            Log::error("Error fetching breed info", [
                'species' => $species,
                'breed' => $breed,
                'error' => $e->getMessage()
            ]);
            return null;
        }
    }

    /**
     * Obtener imagen por raza específica
     */
    public function fetchBreedImage(string $species, string $breed): ?string
    {
        try {
            Log::info('Iniciando búsqueda de imagen en API', [
                'species' => $species,
                'breed' => $breed
            ]);

            $apiKey = 'live_mzJ4KTuSnd83BKHeu0598bGHOWVMie5gViPeMlOwU3ev2Df109kB7MXu81u6vnOo';

            if (strtolower($species) === 'perro' || strtolower($species) === 'dog') {
                // Buscar raza con SSL desactivado para desarrollo
                $breedResponse = Http::withOptions([
                    'verify' => false, // ⚠️ Solo para desarrollo
                    'timeout' => 30,
                ])
                    ->withHeaders(['x-api-key' => $apiKey])
                    ->get("https://api.thedogapi.com/v1/breeds/search", [
                        'q' => $breed
                    ]);

                Log::info('Respuesta de búsqueda de raza', [
                    'status' => $breedResponse->status(),
                    'successful' => $breedResponse->successful(),
                    'json' => $breedResponse->json()
                ]);

                if ($breedResponse->successful() && !empty($breedResponse->json())) {
                    $breedData = $breedResponse->json()[0];
                    $breedId = $breedData['id'] ?? null;

                    if ($breedId) {
                        // Buscar imagen específica de la raza
                        $imageResponse = Http::withOptions([
                            'verify' => false, // ⚠️ Solo para desarrollo
                            'timeout' => 30,
                        ])
                            ->withHeaders(['x-api-key' => $apiKey])
                            ->get("https://api.thedogapi.com/v1/images/search", [
                                'breed_ids' => $breedId,
                                'limit' => 1
                            ]);

                        if ($imageResponse->successful()) {
                            $imageData = $imageResponse->json();
                            $imageUrl = $imageData[0]['url'] ?? null;

                            Log::info('Imagen encontrada', ['url' => $imageUrl]);
                            return $imageUrl;
                        }
                    }
                }
            } elseif (strtolower($species) === 'gato' || strtolower($species) === 'cat') {
                // Similar para gatos
                $breedResponse = Http::withOptions([
                    'verify' => false, // ⚠️ Solo para desarrollo
                    'timeout' => 30,
                ])
                    ->withHeaders(['x-api-key' => $apiKey])
                    ->get("https://api.thecatapi.com/v1/breeds/search", [
                        'q' => $breed
                    ]);

                if ($breedResponse->successful() && !empty($breedResponse->json())) {
                    $breedData = $breedResponse->json()[0];
                    $breedId = $breedData['id'] ?? null;

                    if ($breedId) {
                        $imageResponse = Http::withOptions([
                            'verify' => false, // ⚠️ Solo para desarrollo
                            'timeout' => 30,
                        ])
                            ->withHeaders(['x-api-key' => $apiKey])
                            ->get("https://api.thecatapi.com/v1/images/search", [
                                'breed_ids' => $breedId,
                                'limit' => 1
                            ]);

                        if ($imageResponse->successful()) {
                            $imageData = $imageResponse->json();
                            return $imageData[0]['url'] ?? null;
                        }
                    }
                }
            }

            return null;

        } catch (\Exception $e) {
            Log::error("Error fetching breed image", [
                'species' => $species,
                'breed' => $breed,
                'error' => $e->getMessage()
            ]);
            throw $e;
        }
    }

    /**
     * Obtener imagen aleatoria desde API externa
     */
    private function fetchRandomImage(string $species): ?string
    {
        try {
            $apiKey = 'live_mzJ4KTuSnd83BKHeu0598bGHOWVMie5gViPeMlOwU3ev2Df109kB7MXu81u6vnOo';

            if (strtolower($species) === 'perro' || strtolower($species) === 'dog') {
                $response = Http::timeout(10)
                    ->withHeaders(['x-api-key' => $apiKey])
                    ->get("https://api.thedogapi.com/v1/images/search", [
                        'limit' => 1
                    ]);
            } elseif (strtolower($species) === 'gato' || strtolower($species) === 'cat') {
                $response = Http::timeout(10)
                    ->withHeaders(['x-api-key' => $apiKey])
                    ->get("https://api.thecatapi.com/v1/images/search", [
                        'limit' => 1
                    ]);
            } else {
                return null;
            }

            if ($response->successful()) {
                $data = $response->json();
                return $data[0]['url'] ?? null;
            }

            return null;

        } catch (\Exception $e) {
            Log::error("Error fetching random image", [
                'species' => $species,
                'error' => $e->getMessage()
            ]);
            return null;
        }
    }
}
