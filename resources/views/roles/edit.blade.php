@extends('layouts.app')

@section('title', 'Editar Rol')

@section('header', 'Editar Rol')

@section('content')
    <div class="container mx-auto px-4 py-8">
        <div class="max-w-md mx-auto bg-white rounded-lg overflow-hidden md:max-w-lg">
            <div class="md:flex">
                <div class="w-full px-6 py-8">
                    <h2 class="text-2xl font-bold text-gray-800 mb-6">Editar Rol</h2>
                    <form action="{{ route('roles.update', $role->rol_id) }}" method="POST">
                        @csrf
                        @if ($errors->any())
                            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                                <strong class="font-bold">¡Oops!</strong>
                                <span class="block sm:inline">{{ $errors->first() }}</span>
                            </div>
                        @endif
                        @method('PUT')

                        <div class="mb-4">
                            <label for="us_dni" class="block text-gray-700 text-sm font-bold mb-2">Nombre del Rol:</label>
                            <input type="text" name="rol_name" id="us_dni" value="{{ $role->rol_name }}" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                        </div>
                        <div class="mb-4">
                            <label for="us_status" class="block text-gray-700 text-sm font-bold mb-2">Estado del Rol:</label>
                            <select name="us_status" id="us_status" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                                <option value="A" {{ $role->rol_status == 'A' ? 'selected' : '' }}>Activo</option>
                                <option value="I" {{ $role->rol_status == 'I' ? 'selected' : '' }}>Inactivo</option>
                            </select>
                        </div>

                        <div class="mb-4">
                            <label class="block text-gray-700 text-sm font-bold mb-2">Permisos:</label>
                            @foreach($permissions as $permission)
                                <div class="flex items-center mb-2">
                                    <input type="checkbox" name="permissions[]" value="{{ $permission->perm_id }}" id="permission{{ $permission->perm_id }}"
                                           {{ $role->permissions->contains($permission->perm_id) ? 'checked' : '' }}
                                           class="form-checkbox h-5 w-5 text-blue-600">
                                    <label for="role{{ $permission->perm_id }}" class="ml-2 text-gray-700">
                                        {{ $permission->perm_name }}
                                    </label>
                                </div>
                            @endforeach
                        </div>

                        <div class="flex items-center justify-between mt-8">
                            <a href="{{ route('roles.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
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
@endsection
