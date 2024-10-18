<?php

namespace App\Services\Interfaces;
use Illuminate\Http\Request;

interface UserServiceInterface
{
    public function register(Request $request);

    public function getAuthenticatedUser($id);

    public function updateUser($id, array $validatedData);


}
