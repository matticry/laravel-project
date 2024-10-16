<?php

namespace App\Services;


use App\Models\User;
use App\Services\Interfaces\UserServiceInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserService implements UserServiceInterface
{

    public function register(Request $request)
    {
        // Validar datos del formulario
        $validated = $request->validate([
            'us_name' => 'required|string|max:255',
            'us_lastName' => 'required|string|max:255',
            'us_dni' => 'required|string|max:10',
            'us_email' => 'required|email|unique:tbl_user,us_email',
            'us_first_phone' => 'required|string|max:10',
            'us_password' => 'required|string|min:8|confirmed',
            'us_image' => 'nullable|image|max:10240', // Limite de 10 MB
        ]);

        // Manejo de imagen
        $imagePath = null;
        if ($request->hasFile('us_image')) {
            $imagePath = $request->file('us_image')->store('profile_images', 'public');
        }

        return User::create([
            'us_name' => $validated['us_name'],
            'us_lastName' => $validated['us_lastName'],
            'us_dni' => $validated['us_dni'],
            'us_first_phone' => $validated['us_first_phone'],
            'us_second_phone' => $request->us_second_phone,
            'us_email' => $validated['us_email'],
            'us_address' => $request->us_address,
            'us_password' => Hash::make($validated['us_password']),
            'us_image' => $imagePath,
        ]);
    }

    public function existsUser(string $dni): bool
    {
        return User::where('us_dni', $dni)->exists();
    }

    public function existsUserByEmail(string $email): bool
    {
        return User::where('us_email', $email)->exists();
    }

}
