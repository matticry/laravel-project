<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\Interfaces\ProfileServiceInterface;
use App\Services\Interfaces\UserServiceInterface;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Laravel\Socialite\Facades\Socialite;

class AuthController extends Controller
{
    protected $userService;
    protected $profileService;

    public function __construct(UserServiceInterface $userService, ProfileServiceInterface $profileService)
    {
        $this->userService = $userService;
        $this->profileService = $profileService;

    }
    public function store(Request $request)
    {
        try {

            $validatedData = $request->validate([
                'us_dni' => 'required|string|max:10',
                'us_name' => 'required|string|max:255',
                'us_lastName' => 'required|string|max:255',
                'us_email' => 'required|email|unique:tbl_user,us_email',
                'us_password' => 'required|string|min:6',
                'roles' => 'array'
            ]);

            $user = $this->profileService->createUser($validatedData);

            if(!$user)
            {
                return redirect()->back()->with('error', 'Error al crear el usuario');
            }

            return redirect()->route('profile.index')->with('success', 'Usuario creado correctamente');
        } catch (ValidationException  $e) {
            $errors = $e->validator->errors()->toArray();

            $errorMessage = "Valida bien estos datos:\n";

            foreach ($errors as $key => $value) {
                $errorMessage .= $key . ": " . implode(", ", $value) . "\n";
            }
            return back()->withErrors('error' . $errorMessage)->withInput();
        }

    }
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback()
    {
        try {
            $user = Socialite::driver('google')->user();
            $googleUser = User::where('google_id', $user->id)->first();

            if ($googleUser) {
                Auth::login($googleUser);
                return redirect()->intended('categories');
            } else {
                $newUser = User::create([
                    'us_name' => $user->name,
                    'us_email' => $user->email,
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
