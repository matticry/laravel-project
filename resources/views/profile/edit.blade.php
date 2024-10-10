@extends('layouts.app')

@section('title', 'Editar Usuario')

@section('header', 'Editar Usuario')

@section('content')
    <div class="container mx-auto px-4 py-8">
        <div class="max-w-md mx-auto bg-white rounded-lg overflow-hidden md:max-w-lg">
            <div class="md:flex">
                <div class="w-full px-6 py-8">
                    <h2 class="text-2xl font-bold text-gray-800 mb-6">Editar Usuario</h2>
                    <form action="{{ route('profile.update', $user->us_id) }}" method="POST">
                        @csrf
                        @if ($errors->any())
                            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                                <strong class="font-bold">¡Oops!</strong>
                                <span class="block sm:inline">{{ $errors->first() }}</span>
                            </div>
                        @endif
                        @method('PUT')

                        <div class="mb-4">
                            <label for="us_dni" class="block text-gray-700 text-sm font-bold mb-2">DNI:</label>
                            <input type="text" name="us_dni" id="us_dni" value="{{ $user->us_dni }}" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                            <span id="dniValidationMessage" class="text-sm mt-1"></span>
                        </div>
                        <div class="mb-4">
                            <label for="us_name" class="block text-gray-700 text-sm font-bold mb-2">Nombre:</label>
                            <input type="text" name="us_name" id="us_name" value="{{ $user->us_name }}" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                        </div>

                        <div class="mb-4">
                            <label for="us_lastName" class="block text-gray-700 text-sm font-bold mb-2">Apellido:</label>
                            <input type="text" name="us_lastName" id="us_lastName" value="{{ $user->us_lastName }}" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                        </div>
                        <div class="mb-4">
                            <label for="us_status" class="block text-gray-700 text-sm font-bold mb-2">Estado:</label>
                            <select name="us_status" id="us_status" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                                <option value="A" {{ $user->us_status == 'A' ? 'selected' : '' }}>Activo</option>
                                <option value="I" {{ $user->us_status == 'I' ? 'selected' : '' }}>Inactivo</option>
                            </select>
                        </div>

                        <div class="mb-4">
                            <label class="block text-gray-700 text-sm font-bold mb-2">Roles:</label>
                            @foreach($roles as $role)
                                <div class="flex items-center mb-2">
                                    <input type="checkbox" name="roles[]" value="{{ $role->rol_id }}" id="role{{ $role->rol_id }}"
                                           {{ $user->roles->contains($role->rol_id) ? 'checked' : '' }}
                                           class="form-checkbox h-5 w-5 text-blue-600">
                                    <label for="role{{ $role->rol_id }}" class="ml-2 text-gray-700">
                                        {{ $role->rol_name }}
                                    </label>
                                </div>
                            @endforeach
                        </div>

                        <div class="flex items-center justify-between mt-8">
                            <a href="{{ route('profile.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                                Cancelar
                            </a>
                            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                                Actualizar Usuario
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script src="{{ asset('/assets/js/email_employee_validator.js') }}" defer></script>
    <script src="{{ asset('/assets/js/cedula-validator.js') }}" defer></script>
@endsection
