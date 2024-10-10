<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\Interfaces\UserServiceInterface;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use Laravel\Socialite\Facades\Socialite;

class AuthController extends Controller
{
    protected $userService;

    public function __construct(UserServiceInterface $userService)
    {
        $this->userService = $userService;
    }
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback()
    {
        try {
            $user = Socialite::driver('google')->user();
            $finduser = User::where('google_id', $user->id)->first();

            if ($finduser) {
                Auth::login($finduser);
                return redirect()->intended('categories');
            } else {
                $newUser = User::create([
                    'us_name' => $user->name,
                    'us_email' => $user->email,
                    'us_image' => $googleUser->avatar, // URL de la imagen de perfil
                    'google_id' => $user->id,
                    'us_password' => encrypt('123456dummy'),
                    'us_status' => 'A',
                ]);

                Auth::login($newUser);
                return redirect()->intended('categories');
            }
        } catch (Exception $e) {
            dd($e->getMessage());
        }
    }

    public function register(Request $request)
    {
        if ($this->userService->existsUser($request->us_dni)) {
            return back()->withErrors(['us_dni' => 'El usuario con este DNI ya existe.'])->withInput();
        }

        if ($this->userService->existsUserByEmail($request->us_email)) {
            return back()->withErrors(['us_email' => 'El usuario con este correo ya está registrado.'])->withInput();
        }

        try {
            $user = $this->userService->register($request);

            if (!$user) {
                return back()->with('error', 'Error al registrar el usuario.')->withInput();
            }

            return back()->with('success', 'Usuario registrado exitosamente');
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return back()->with('error', 'Error al registrar el usuario por parte del servidor')->withInput();
        }
    }

    public function showLoginForm()
    {
        return view('auth.login');
    }
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'us_dni' => ['required'],
            'us_password' => ['required'],
        ]);

        $key = Str::lower($credentials['us_dni']) . '|' . $request->ip();

        if (RateLimiter::tooManyAttempts($key, 3)) {
            $seconds = RateLimiter::availableIn($key);
            return back()->withErrors([
                'us_dni' => "Demasiados intentos fallidos. Por favor, intente nuevamente en {$seconds} segundos.",
            ])->with('lockout', true);
        }

        if (Auth::attempt([
            'us_dni' => $credentials['us_dni'],
            'password' => $credentials['us_password']
        ])) {
            RateLimiter::clear($key);

            $user = Auth::user();
            Auth::loginUsingId($user->us_id);
            $request->session()->regenerate();

            Log::info('Login exitoso', [
                'user_id' => Auth::id(),
                'user' => Auth::user(),
                'session' => $request->session()->all()
            ]);

            return redirect()->intended('categories');
        }

        RateLimiter::hit($key, 300); // 300 segundos = 5 minutos

        Log::warning('Login fallido', [
            'us_dni' => $credentials['us_dni']
        ]);

        return back()->withErrors([
            'us_dni' => 'Las credenciales proporcionadas no coinciden con nuestros registros.',
        ]);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login');
    }

}
