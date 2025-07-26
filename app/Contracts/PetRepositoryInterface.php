<?php

namespace App\Contracts;

use App\Models\Pet;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

interface PetRepositoryInterface
{

    public function getAllPaginated(int $perPage = 15, array $filters = []): LengthAwarePaginator;

    public function getAll(array $filters = []): Collection;


    public function findById(int $id): ?Pet;


    public function findByIdWithOwner(int $id): ?Pet;

    public function create(array $data): Pet;

    /**
     * Actualizar mascota
     */
    public function update(int $id, array $data): ?Pet;


    public function delete(int $id): bool;


    public function changeStatus(int $id, string $status): bool;

    public function getByUser(int $userId): Collection;


    public function getActiveByUser(int $userId): Collection;


    public function getBySpecies(string $species): Collection;


    public function searchByName(string $name): Collection;


    public function countByUser(int $userId): int;

    public function getStatistics(): array;
}
