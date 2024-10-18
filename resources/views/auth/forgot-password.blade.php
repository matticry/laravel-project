<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Recuperar Contraseña</title>
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-blue-100 font-nunito">
<div class="min-h-screen flex items-center justify-center">
    <div class="w-full max-w-md">
        <form class="bg-white shadow-xl rounded-lg px-8 pt-6 pb-8 mb-4" method="POST" action="{{ route('password.email') }}">
            @csrf
            <h2 class="mb-6 text-center text-3xl font-extrabold text-gray-900">Recuperar Contraseña</h2>

            @if (session('status'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                    <span class="block sm:inline">{{ session('status') }}</span>
                </div>
            @endif

            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="email">
                    Correo Electrónico
                </label>
                <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('us_email') border-red-500 @enderror"
                       id="email"
                       type="email"
                       name="us_email"
                       value="{{ old('us_email') }}"
                       required
                       autocomplete="us_email"
                       autofocus>

                @error('us_email')
                <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex items-center justify-between mb-6">
                <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline" type="submit">
                    Enviar Enlace de Recuperación
                </button>
            </div>

            <div class="text-center">
                <a class="font-bold text-sm text-blue-500 hover:text-blue-800" href="{{ route('login') }}">
                    Volver al inicio de sesión
                </a>
            </div>
        </form>
    </div>
</div>
</body>
</html>
