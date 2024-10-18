<?php

namespace App\Services;

use App\Models\Profile;
use App\Services\Interfaces\ProfileServiceInterface;
use Illuminate\Support\Facades\Hash;

class ProfileService implements ProfileServiceInterface
{

    /**
     * @return mixed
     */
    public function getAllUsers()
    {
        return Profile::with('roles')->get();
    }

    /**
     * @param $id
     * @return mixed
     */
    public function getUserById($id)
    {
        return Profile::with('roles')->findOrFail($id);
    }

    /**
     * @param array $userData
     * @return mixed
     */
    public function createUser(array $userData)
    {
        $userData['us_password'] = Hash::make($userData['us_password']);
        $user = Profile::create($userData);
        if (isset($userData['roles'])) {
            $this->assignRoles($user->us_id, $userData['roles']);
        }
        return $user;
    }

    /**
     * @param $id
     * @param array $userData
     * @return mixed
     */
    public function updateUser($id, array $userData)
    {
        $user = Profile::findOrFail($id);
        if (isset($userData['us_password'])) {
            $userData['us_password'] = Hash::make($userData['us_password']);
        }
        $user->update($userData);
        if (isset($userData['roles'])) {
            $this->assignRoles($user->us_id, $userData['roles']);
        }
        return $user;
    }

    /**
     * @param $id
     * @return mixed
     */
    public function deleteUser($id)
    {
        $user = Profile::findOrFail($id);
        $user->roles()->detach();
        return $user->delete();
    }

    public function assignRoles($userId, array $roleIds)
    {
        $user = Profile::findOrFail($userId);
        $user->roles()->sync($roleIds);
    }

    public function existsUserByDni($dni): bool
    {
        return Profile::where('us_dni', $dni)->exists();

    }
    public function existsUserByEmail($email): bool
    {
        return Profile::where('us_email', $email)->exists();

    }
}
