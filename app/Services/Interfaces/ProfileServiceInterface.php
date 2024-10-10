<?php

namespace App\Services\Interfaces;

interface ProfileServiceInterface
{
    public function getAllUsers();
    public function getUserById($id);
    public function createUser(array $userData);
    public function updateUser($id, array $userData);
    public function deleteUser($id);
    public function assignRoles($userId, array $roleIds);

}
