<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Registro de Usuario</title>
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@2.8.2/dist/alpine.min.js" defer></script>
    <script src="{{ asset('/assets/js/cedula-validator.js') }}" defer></script>
    <script src="{{ asset('/assets/js/email-validator.js') }}" defer></script>
    <script src="{{ asset('/assets/js/password-validator.js') }}" defer></script>

    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body class="bg-blue-200 font-nunito min-h-screen flex items-center justify-center p-4">
<div class="bg-white rounded-lg shadow-xl p-8 max-w-2xl w-full">
    <h2 class="text-3xl font-bold text-center  mb-8 text-blue-600 ">Registro de Usuario</h2>
    <p class="text-sm text-gray-600 mb-4">Los campos marcados con * son obligatorios</p>
    <form action="{{ route('register.submit') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf
        @if (session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                <strong class="font-bold">¡Éxito!</strong>
                <span class="block sm:inline">{{ session('success') }}</span>
            </div>
        @endif

    @if ($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                <strong class="font-bold">¡Oops!</strong>
                <span class="block sm:inline">{{ $errors->first() }}</span>
            </div>
        @endif
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="col-span-full">
                <label for="us_dni" class="block text-sm font-medium text-gray-700">Cédula *</label>
                <input type="text" name="us_dni" id="us_dni" placeholder="Ingrese su cédula" class="mt-1 block w-full border-2 border-blue-200 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 h-12 px-3" required>
                <span id="dniValidationMessage" class="text-sm mt-1"></span>
            </div>
            <div>
                <label for="us_name" class="block text-sm font-medium text-gray-700">Nombre *</label>
                <input type="text" name="us_name" id="us_name" placeholder="Ingrese su nombre" class="mt-1 block w-full border-2 border-blue-200 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 h-12 px-3" required>
            </div>
            <div>
                <label for="us_lastName" class="block text-sm font-medium text-gray-700">Apellido *</label>
                <input type="text" name="us_lastName" id="us_lastName" placeholder="Ingrese su apellido" class="mt-1 block w-full border-2 border-blue-200 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 h-12 px-3" required>
            </div>
            <div>
                <label for="us_email" class="block text-sm font-medium text-gray-700">Email *</label>
                <input type="email" name="us_email" id="us_email" placeholder="ejemplo@correo.com" class="mt-1 block w-full border-2 border-blue-200 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 h-12 px-3" required>
                <span id="emailValidationMessage" class="text-sm mt-1"></span>

            </div>
            <div>
                <label for="us_first_phone" class="block text-sm font-medium text-gray-700">Teléfono Principal *</label>
                <input type="tel" name="us_first_phone" id="us_first_phone" placeholder="Ej: 0991234567" class="mt-1 block w-full border-2 border-blue-200 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 h-12 px-3" required>
            </div>
            <div>
                <label for="us_second_phone" class="block text-sm font-medium text-gray-700">Teléfono Secundario</label>
                <input type="tel" name="us_second_phone" id="us_second_phone" placeholder="Ej: 0987654321" class="mt-1 block w-full border-2 border-blue-200 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 h-12 px-3">
            </div>
            <div class="col-span-full">
                <label for="us_address" class="block text-sm font-medium text-gray-700">Dirección *</label>
                <input type="text" name="us_address" id="us_address" placeholder="Ingrese su dirección completa" class="mt-1 block w-full border-2 border-blue-200 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 h-12 px-3" required>
            </div>
            <div class="col-span-full">
                <label for="us_password" class="block text-sm font-medium text-gray-700">Contraseña *</label>
                <input type="password" name="us_password" id="us_password" placeholder="Mínimo 8 caracteres" class="mt-1 block w-full border-2 border-blue-200 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 h-12 px-3" required>
            </div>
            <div class="col-span-full">
                <label for="us_password_confirmation" class="block text-sm font-medium text-gray-700">Confirmar Contraseña *</label>
                <input type="password" name="us_password_confirmation" id="us_password_confirmation" placeholder="Repita su contraseña" class="mt-1 block w-full border-2 border-blue-200 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 h-12 px-3" required>
            </div>
            <div id="password-strength" class="col-span-full mt-2 overflow-hidden transition-all duration-300 ease-in-out" style="max-height: 0;">
                <ul class="text-sm">
                    <li id="length" class="text-red-500"><span class="icon">✕</span> Al menos 8 caracteres</li>
                    <li id="lowercase" class="text-red-500"><span class="icon">✕</span> Contiene una letra minúscula</li>
                    <li id="uppercase" class="text-red-500"><span class="icon">✕</span> Contiene una letra mayúscula</li>
                    <li id="number" class="text-red-500"><span class="icon">✕</span> Contiene un número</li>
                    <li id="symbol" class="text-red-500"><span class="icon">✕</span> Contiene un símbolo</li>
                    <li id="match" class="text-red-500"><span class="icon">✕</span> Las contraseñas coinciden</li>
                </ul>
            </div>
        </div>

        <div x-data="{ imageUrl: null }" class="col-span-full">
            <label for="us_image" class="block text-sm font-medium text-gray-700">Foto de perfil</label>
            <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-blue-200 border-dashed rounded-md">
                <div class="space-y-1 text-center">
                    <img x-show="imageUrl" :src="imageUrl" class="mx-auto h-32 w-32 object-cover rounded-full" x-cloak>
                    <svg x-show="!imageUrl" class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48" aria-hidden="true">
                        <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                    <div class="flex text-sm text-gray-600">
                        <label for="us_image" class="relative cursor-pointer bg-white rounded-md font-medium text-blue-600 hover:text-blue-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-blue-500">
                            <span>Subir un archivo</span>
                            <input id="us_image" name="us_image" type="file" class="sr-only" @change="imageUrl = URL.createObjectURL($event.target.files[0])">
                        </label>
                        <p class="pl-1">o arrastrar y soltar</p>
                    </div>
                    <p class="text-xs text-gray-500">PNG, JPG, GIF hasta 10MB</p>
                </div>
            </div>
        </div>

        <div>
            <button type="submit" class="w-full flex justify-center py-3 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                Registrarse
            </button>
        </div>
    </form>
    <div class="mt-6 text-center">
        <a href="{{ route('login') }}" class="font-medium text-blue-600 hover:text-blue-500">
            ¿Ya tienes una cuenta? Inicia sesión
        </a>
    </div>
</div>


</body>
</html>
