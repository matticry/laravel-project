<?php

namespace App\Services;


use App\Models\User;
use App\Services\Interfaces\UserServiceInterface;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

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

    public function getAuthenticatedUser($id)
    {
        return User::findOrFail($id);
    }


    public function updateUser($id, array $data)
    {
        $user = User::findOrFail($id);

        $validator = Validator::make($data, [
            'us_name' => 'nullable|string|max:255',
            'us_lastName' => 'nullable|string|max:255',
            'us_dni' => 'nullable|string|max:10|unique:tbl_user,us_dni,' . $user->us_id . ',us_id',
            'us_email' => 'nullable|email|unique:tbl_user,us_email,' . $user->us_id . ',us_id',
            'us_address' => 'nullable|string|max:255',
            'us_first_phone' => 'required|digits:10',
            'us_second_phone' => 'nullable|digits:10',
            'us_image' => 'nullable|image|max:10240', // Limite de 10 MB
        ]);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }

        $validated = $validator->validated();

        // Manejo de imagen
        if (isset($data['us_image']) && $data['us_image'] instanceof UploadedFile) {
            $imagePath = $data['us_image']->store('profile_images', 'public');
            $validated['us_image'] = $imagePath;
        }

        $user->update($validated);

        return $user;
    }

    /**
     * @throws Exception
     */
    public function changePassword(User $user, string $currentPassword, string $newPassword): bool
    {
        // Validar la contraseña actual
        if (!Hash::check($currentPassword, $user->us_password)) {
            throw new Exception('La contraseña actual es incorrecta.');
        }

        // Validar la nueva contraseña
        $validator = Validator::make(
            ['new_password' => $newPassword, 'new_password_confirmation' => request('new_password_confirmation')],
            [
                'new_password' => [
                    'required',
                    'string',
                    'min:8',
                    'regex:/[a-z]/',
                    'regex:/[A-Z]/',
                    'regex:/[0-9]/',
                    'regex:/[@$!%*#?&]/',
                    'confirmed',
                ],
            ]
        );

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }

        // Actualizar la contraseña
        $user->us_password = Hash::make($newPassword);
        $user->save();

        return true;
    }

}
