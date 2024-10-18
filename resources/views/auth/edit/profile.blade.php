@extends('layouts.app')

@section('title', 'Gestión de Perfil')

@section('header', 'Gestión de Perfil')

@section('content')
    <div x-data="{
        activeTab: 'edit-profile',
        }" class="mb-6">
        <div class="border-b border-blue-200">
            <nav class="-mb-px flex">
                    <a @click.prevent="activeTab = 'edit-profile'" :class="{'border-blue-500 text-blue-800': activeTab === 'edit-profile'}" class="cursor-pointer border-b-2 border-transparent py-4 px-6 inline-block font-medium text-sm leading-5 text-blue-600 hover:text-blue-800 hover:border-blue-300 focus:outline-none focus:text-blue-800 focus:border-blue-300">
                        Editar Perfil
                    </a>
                <a href="{{ route('settings.profile', ['id' => auth()->user()->us_id]) }}" class="cursor-pointer border-b-2 border-transparent py-4 px-6 inline-block font-medium text-sm leading-5 text-blue-600 hover:text-blue-800 hover:border-blue-300 focus:outline-none focus:text-blue-800 focus:border-blue-300">
                    Configuracion del Sistema
                </a>

            </nav>
        </div>
    </div>

    @if (session('success'))
        <div x-data="{ show: true }" x-show="show" class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded-md relative mb-4" role="alert">
            <button class="absolute top-2 right-2 text-green-700 hover:bg-green-200 p-1 rounded transition duration-300">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
            <div class="flex items-center">
                <svg class="h-5 w-5 text-green-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                </svg>
                <strong class="font-bold">¡Éxito!</strong>
            </div>
            <span class="block mt-2">{{ session('success') }}</span>
            <div class="mt-3">
                <a href="{{ route('calendario.ordenes') }}" class="text-green-700 hover:bg-green-200 px-2 py-1 rounded transition duration-300 mr-3">Ver detalles</a>
                <button @click="show = false" class="text-green-700 hover:bg-green-200 px-2 py-1 rounded transition duration-300">Cerrar</button>
            </div>
        </div>
    @endif

    @if ($errors->any())
        <x-bladewind::alert type="error">
            <strong class="font-bold">¡Oops!</strong>
            <span class="block sm:inline">{{ $errors->first() }}</span>
        </x-bladewind::alert>
    @endif

    <div x-show="activeTab === 'edit-profile'" class="bg-blue-50 min-h-screen p-6">
        <div class="max-w-4xl mx-auto bg-white rounded-lg shadow-md overflow-hidden">
            <div class="p-6">
                <h2 class="text-2xl font-semibold text-blue-800 mb-6">Información Personal</h2>

                <form action="{{ route('update.profile', ['id' => auth()->user()->us_id]) }}"  method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Avatar -->
                        <div x-data="{ previewUrl: null }" class="col-span-2 flex items-center space-x-4">
                            <div class="w-20 h-20 rounded-full overflow-hidden">
                                <img
                                    x-bind:src="previewUrl ? previewUrl : '{{ auth()->user()->us_image ? Storage::url(auth()->user()->us_image) : 'https://ui-avatars.com/api/?name=' . urlencode(auth()->user()->us_name) . '&size=80&background=random' }}'"
                                    alt="Avatar"
                                    class="w-full h-full object-cover">
                            </div>
                            <div>
                                <label for="us_image" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600 cursor-pointer">
                                    Cambiar avatar
                                </label>
                                <input
                                    id="us_image"
                                    type="file"
                                    name="us_image"
                                    class="hidden"
                                    accept="image/*"
                                    @change="const file = $event.target.files[0];
                     if (file) {
                         const reader = new FileReader();
                         reader.onload = (e) => { previewUrl = e.target.result };
                         reader.readAsDataURL(file);
                     }">
                                <p class="text-sm text-blue-600 mt-1">JPG, GIF o PNG. 1MB máx.</p>
                            </div>
                        </div>

                        <!-- Nombre -->
                        <div>
                            <label for="us_name" class="block text-sm font-medium text-blue-600">Nombre</label>
                            <input type="text" name="us_name" id="us_name" value="{{ $user->us_name }}"
                                   class="mt-1 block w-full rounded-md border-blue-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200"
                                {{ $user->us_dni ? 'readonly' : '' }}>
                        </div>

                        <!-- Apellido -->
                        <div>
                            <label for="us_lastName" class="block text-sm font-medium text-blue-600">Apellido</label>
                            <input type="text" name="us_lastName" id="us_lastName" value="{{ $user->us_lastName }}"
                                   class="mt-1 block w-full rounded-md border-blue-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200"
                                {{ $user->us_dni ? 'readonly' : '' }}>
                        </div>

                        <!-- Dirección -->
                        <div class="col-span-2">
                            <label for="us_address" class="block text-sm font-medium text-blue-600">Dirección</label>
                            <input type="text" name="us_address" placeholder="Ingrese su dirección completa" id="us_address" value="{{ auth()->user()->us_address }}" class="mt-1 block w-full rounded-md border-blue-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200">
                        </div>

                        <!-- DNI -->
                        <div>
                            <label for="us_dni" class="block text-sm font-medium text-blue-600">Cédula</label>
                            <input type="text" name="us_dni" id="us_dni" value="{{ $user->us_dni }}"
                                   class="mt-1 block w-full rounded-md border-blue-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200"
                                {{ $user->us_dni ? 'readonly' : '' }}>
                        </div>

                        <!-- Email -->
                        <div class="relative">
                            <label for="us_email" class="block text-sm font-medium text-blue-600">Correo electrónico</label>
                            <input type="email" name="us_email" id="us_email" value="{{ $user->us_email }}" class="mt-1 block w-full rounded-md border-blue-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200" {{ $user->email_verified_at ? 'readonly' : '' }}>
                            @if(!$user->email_verified_at)
                                <button type="button" onclick="verifyEmail()" class="absolute right-2 top-8 bg-blue-500 text-white px-2 py-1 rounded text-sm">
                                    Verificar correo
                                </button>
                            @endif
                        </div>

                        <!-- Teléfono principal -->
                        <div>
                            <label for="us_first_phone" class="block text-sm font-medium text-blue-600">Teléfono principal</label>
                            <input type="tel"
                                   name="us_first_phone"
                                   id="us_first_phone"
                                   placeholder="Ej: 0991234567"
                                   value="{{ auth()->user()->us_first_phone }}"
                                   class="mt-1 block w-full rounded-md border-blue-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200"
                                   pattern="[0-9]{10}"
                                   maxlength="10"
                                   oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 10)">
                        </div>

                        <!-- Teléfono secundario -->
                        <div>
                            <label for="us_second_phone" class="block text-sm font-medium text-blue-600">Teléfono secundario</label>
                            <input type="tel"
                                   name="us_second_phone"
                                   id="us_second_phone"
                                   placeholder="Ej: 0987654321"
                                   value="{{ auth()->user()->us_second_phone }}"
                                   class="mt-1 block w-full rounded-md border-blue-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200"
                                   pattern="[0-9]{10}"
                                   maxlength="10"
                                   oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 10)">
                        </div>

                        <!-- Botón de guardar -->
                        <div class="col-span-2">
                            <button type="submit" class="w-full bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
                                Guardar cambios
                            </button>
                        </div>
                    </div>
                </form>

                <!-- Cambiar contraseña -->
                <div class="mt-12">
                    <h3 class="text-xl font-semibold text-blue-800 mb-4">Cambiar contraseña</h3>
                    <form action="{{route('change.password')}}"  method="POST">
                        @csrf
                        @method('POST')

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="current_password" class="block text-sm font-medium text-blue-600">Contraseña actual</label>
                                <input type="password" name="current_password" placeholder="Ingrese su contraseña actual" id="current_password" class="mt-1 block w-full rounded-md border-blue-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200">
                            </div>

                            <div>
                                <label for="new_password" class="block text-sm font-medium text-blue-600">Nueva contraseña</label>
                                <input type="password" name="new_password" id="new_password" placeholder="Ingrese su nueva contraseña" class="mt-1 block w-full rounded-md border-blue-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200">
                            </div>

                            <div>
                                <label for="new_password_confirmation" class="block text-sm font-medium text-blue-600">Confirmar nueva contraseña</label>
                                <input type="password" name="new_password_confirmation" placeholder="Ingrese nuevamente su nueva contraseña" id="new_password_confirmation" class="mt-1 block w-full rounded-md border-blue-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200">
                            </div>

                            <div class="col-span-2">
                                <button type="submit" class="w-full bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
                                    Cambiar contraseña
                                </button>
                            </div>
                        </div>
                    </form>
                </div>

                <!-- Eliminar cuenta -->
                <div class="mt-12">
                    <h3 class="text-xl font-semibold text-red-600 mb-4">Eliminar cuenta</h3>
                    <div class="bg-red-50 border border-red-200 rounded-md p-4">
                        <p class="text-red-700">Eliminar tu cuenta es una acción permanente y no se puede deshacer. Todos tus datos serán borrados y no podrás acceder a nuestros servicios.</p>
                        <button onclick="confirmDeleteAccount()" class="mt-4 bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600">
                            Eliminar mi cuenta
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
