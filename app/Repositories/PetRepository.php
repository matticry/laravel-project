<?php

namespace App\Repositories;

use App\Contracts\PetRepositoryInterface;
use App\Models\Pet;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PetRepository implements PetRepositoryInterface
{
    protected Pet $model;

    public function __construct(Pet $model)
    {
        $this->model = $model;
    }

    /**
     * Obtener todas las mascotas con paginación
     */
    public function getAllPaginated(int $perPage = 15, array $filters = []): LengthAwarePaginator
    {
        $query = $this->model->with('owner');

        // Aplicar filtros
        $query = $this->applyFilters($query, $filters);

        return $query->orderBy('created_at', 'desc')->paginate($perPage);
    }

    /**
     * Obtener todas las mascotas
     */
    public function getAll(array $filters = []): Collection
    {
        $query = $this->model->with('owner');
        $query = $this->applyFilters($query, $filters);

        return $query->orderBy('created_at', 'desc')->get();
    }

    /**
     * Buscar mascota por ID
     */
    public function findById(int $id): ?Pet
    {
        try {
            return $this->model->findOrFail($id);
        } catch (\Exception $e) {
            Log::error("Pet not found with ID: {$id}", ['error' => $e->getMessage()]);
            return null;
        }
    }

    /**
     * Buscar mascota por ID con relaciones
     */
    public function findByIdWithOwner(int $id): ?Pet
    {
        try {
            return $this->model->with('owner')->findOrFail($id);
        } catch (\Exception $e) {
            Log::error("Pet with owner not found with ID: {$id}", ['error' => $e->getMessage()]);
            return null;
        }
    }

    /**
     * Crear nueva mascota
     */
    public function create(array $data): Pet
    {
        try {
            DB::beginTransaction();

            $petData = [
                'name_pet' => $data['name_pet'],
                'species_pet' => $data['species_pet'],
                'breed_pet' => $data['breed_pet'],
                'age_pet' => $data['age_pet'],
                'status_pet' => $data['status_pet'] ?? 'A',
                'id_usu' => $data['id_usu'],
            ];

            // Agregar imagen si existe
            if (isset($data['image_pet'])) {
                $petData['image_pet'] = $data['image_pet'];
                $petData['image_source'] = $data['image_source'] ?? 'upload';
            }

            $pet = $this->model->create($petData);

            DB::commit();
            Log::info("Pet created successfully", [
                'pet_id' => $pet->id_pet,
                'image_pet' => $pet->image_pet,
                'image_source' => $pet->image_source
            ]);

            return $pet;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Error creating pet", ['data' => $data, 'error' => $e->getMessage()]);
            throw $e;
        }
    }

    /**
     * Actualizar mascota
     */
    public function update(int $id, array $data): ?Pet
    {
        try {
            DB::beginTransaction();

            $pet = $this->findById($id);
            if (!$pet) {
                return null;
            }

            $updateData = [
                'name_pet' => $data['name_pet'] ?? $pet->name_pet,
                'species_pet' => $data['species_pet'] ?? $pet->species_pet,
                'breed_pet' => $data['breed_pet'] ?? $pet->breed_pet,
                'age_pet' => $data['age_pet'] ?? $pet->age_pet,
                'status_pet' => $data['status_pet'] ?? $pet->status_pet,
                'id_usu' => $data['id_usu'] ?? $pet->id_usu,
            ];

            if (isset($data['image_pet'])) {
                $updateData['image_pet'] = $data['image_pet'];
                $updateData['image_source'] = $data['image_source'] ?? 'upload';
            }

            $pet->update($updateData);

            DB::commit();
            Log::info("Pet updated successfully", [
                'pet_id' => $id,
                'image_pet' => $pet->image_pet ?? 'no change'
            ]);

            return $pet->fresh();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Error updating pet", ['pet_id' => $id, 'data' => $data, 'error' => $e->getMessage()]);
            throw $e;
        }
    }
    public function delete(int $id): bool
    {
        try {
            $pet = $this->findById($id);
            if (!$pet) {
                return false;
            }

            $pet->delete();
            Log::info("Pet deleted successfully", ['pet_id' => $id]);

            return true;
        } catch (\Exception $e) {
            Log::error("Error deleting pet", ['pet_id' => $id, 'error' => $e->getMessage()]);
            return false;
        }
    }

    /**
     * Cambiar estado de mascota
     */
    public function changeStatus(int $id, string $status): bool
    {
        try {
            $pet = $this->findById($id);
            if (!$pet) {
                return false;
            }

            $pet->update(['status_pet' => $status]);
            Log::info("Pet status changed", ['pet_id' => $id, 'new_status' => $status]);

            return true;
        } catch (\Exception $e) {
            Log::error("Error changing pet status", ['pet_id' => $id, 'status' => $status, 'error' => $e->getMessage()]);
            return false;
        }
    }

    public function getByUser(int $userId): Collection
    {
        return $this->model->where('id_usu', $userId)
            ->orderBy('created_at', 'desc')
            ->get();
    }

    /**
     * Obtener mascotas activas por usuario
     */
    public function getActiveByUser(int $userId): Collection
    {
        return $this->model->where('id_usu', $userId)
            ->where('status_pet', 'A')
            ->orderBy('created_at', 'desc')
            ->get();
    }

    /**
     * Buscar mascotas por especie
     */
    public function getBySpecies(string $species): Collection
    {
        return $this->model->where('species_pet', 'like', "%{$species}%")
            ->where('status_pet', 'A')
            ->with('owner')
            ->get();
    }

    /**
     * Buscar mascotas por nombre
     */
    public function searchByName(string $name): Collection
    {
        return $this->model->where('name_pet', 'like', "%{$name}%")
            ->where('status_pet', 'A')
            ->with('owner')
            ->get();
    }

    /**
     * Contar mascotas por usuario
     */
    public function countByUser(int $userId): int
    {
        return $this->model->where('id_usu', $userId)
            ->where('status_pet', 'A')
            ->count();
    }

    /**
     * Obtener estadísticas
     */
    public function getStatistics(): array
    {
        $stats = [
            'total' => $this->model->count(),
            'active' => $this->model->where('status_pet', 'A')->count(),
            'inactive' => $this->model->where('status_pet', 'I')->count(),
            'by_species' => $this->model->select('species_pet', DB::raw('count(*) as count'))
                ->where('status_pet', 'A')
                ->groupBy('species_pet')
                ->get()
                ->pluck('count', 'species_pet')
                ->toArray(),
        ];

        return $stats;
    }

    /**
     * Aplicar filtros a la query
     */
    private function applyFilters($query, array $filters)
    {
        if (!empty($filters['status'])) {
            $query->where('status_pet', $filters['status']);
        }

        if (!empty($filters['species'])) {
            $query->where('species_pet', 'like', "%{$filters['species']}%");
        }

        if (!empty($filters['user_id'])) {
            $query->where('id_usu', $filters['user_id']);
        }

        if (!empty($filters['name'])) {
            $query->where('name_pet', 'like', "%{$filters['name']}%");
        }

        if (!empty($filters['min_age'])) {
            $query->where('age_pet', '>=', $filters['min_age']);
        }

        if (!empty($filters['max_age'])) {
            $query->where('age_pet', '<=', $filters['max_age']);
        }

        return $query;
    }
}
