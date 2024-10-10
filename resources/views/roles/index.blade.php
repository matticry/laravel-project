@extends('layouts.app')

@section('title', 'Gestión de Roles')

@section('header', 'Gestión de Roles')

@section('content')

    <div x-data="{ activeTab: 'roles', activeModal: null, userData: {} }" class="mb-6">
        <!-- Tabs de navegación -->
        <div class="border-b border-gray-200">
            <nav class="-mb-px flex">
                <a href="{{ route('profile.index') }}"
                   class="cursor-pointer border-b-2 border-transparent py-4 px-6 inline-block font-medium text-sm leading-5 text-gray-500 hover:text-gray-700 hover:border-gray-300 focus:outline-none focus:text-gray-700 focus:border-gray-300">
                    Usuarios
                </a>
                <a @click.prevent="activeTab = 'roles'"
                   :class="{'border-blue-500 text-blue-600': activeTab === 'roles'}"
                   class="cursor-pointer border-b-2 border-transparent py-4 px-6 inline-block font-medium text-sm leading-5 text-gray-500 hover:text-gray-700 hover:border-gray-300 focus:outline-none focus:text-gray-700 focus:border-gray-300">
                    Roles y Permisos
                </a>
            </nav>
        </div>
        @if (session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4"
                 role="alert">
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

        <!-- Contenido de cada sección -->
        <div x-show="activeTab === 'roles'" class="mt-6">
            <h2 class="text-2xl font-semibold text-gray-900">Usuarios</h2>
            <div class="mb-4 mt-4">
                <button @click="activeModal = 'create'"
                        class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded">
                    Crear Nuevo Rol
                </button>
            </div>
            <div class="mb-4 mt-4 flex items-center space-x-4">
                <!-- Formulario de búsqueda -->
                <form action="{{ route('roles.index') }}" method="GET" class="flex space-x-2">
                    <!-- Campo para código de empleado -->

                    <!-- Campo para nombre de empleado -->
                    <div>
                        <label for="name_emplo" class="block text-sm font-medium text-gray-700">Nombre:</label>
                        <input type="text" name="name_emplo" id="name_emplo"
                               class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                               placeholder="Filtrar por nombre" value="{{ request()->get('name_emplo') }}">
                    </div>

                    <!-- Botón para realizar la búsqueda -->
                    <div>
                        <button type="submit"
                                class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 mt-4 rounded focus:outline-none focus:shadow-outline">
                            Buscar
                        </button>
                    </div>
                </form>
            </div>

            <!-- Tabla de profile -->
            <div class="overflow-x-auto bg-white rounded-lg shadow overflow-y-auto relative">
                <table class="border-collapse table-auto w-full whitespace-no-wrap bg-white table-striped relative">
                    <thead>
                    <tr class="text-left">
                        <th class="bg-blue-100 sticky top-0 border-b border-gray-200 px-6 py-3 text-blue-600 font-bold tracking-wider uppercase text-xs">
                            ID
                        </th>
                        <th class="bg-blue-100 sticky top-0 border-b border-gray-200 px-6 py-3 text-blue-600 font-bold tracking-wider uppercase text-xs">
                            Nombre del Rol
                        </th>
                        <th class="bg-blue-100 sticky top-0 border-b border-gray-200 px-6 py-3 text-blue-600 font-bold tracking-wider uppercase text-xs">
                            Permisos del Rol
                        </th>

                        <th class="bg-blue-100 sticky top-0 border-b border-gray-200 px-6 py-3 text-blue-600 font-bold tracking-wider uppercase text-xs">
                            Estado
                        </th>
                        <th class="bg-blue-100 sticky top-0 border-b border-gray-200 px-6 py-3 text-blue-600 font-bold tracking-wider uppercase text-xs">
                            Acciones
                        </th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($roles as $role)
                        <tr>
                            <td class="border-dashed border-t border-gray-200 px-6 py-4">
                                @if($role->rol_id)
                                    {{ $role->rol_id }}
                                @else
                                    <span
                                        class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800"> No actualizado</span>

                                @endif
                            </td>
                            <td class="border-dashed border-t border-gray-200 px-6 py-4">{{ $role->rol_name ?? 'No actualizado' }}</td>
                            <td class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full mt-5 bg-blue-100">
                                @if($role->permissions->isNotEmpty())
                                    {{ $role->permissions->pluck('perm_name')->implode(', ') }}
                                @else
                                    <span
                                        class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800 italic text-gray-500 text-sm"> No actualizado</span>
                                @endif
                            </td>
                            <td class="border-dashed border-t border-gray-200 px-6 py-4">
                                @if($role->rol_status)
                                    <span
                                        class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $role->rol_status == 'A' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                    {{ $role->rol_status == 'A' ? 'Activo' : 'Inactivo' }}
                </span>
                                @else
                                    <span
                                        class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800"> No actualizado</span>

                                @endif
                            </td>
                            <td class="border-dashed border-t border-gray-200 px-6 py-4">
                                <div class="flex space-x-2">
                                    <a href="{{ route('roles.edit', $role->rol_id) }}"
                                       class="bg-green-100 text-green-600 hover:bg-green-200 rounded-full p-2">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                             xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                  d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                        </svg>
                                    </a>
                                    <form action="{{ route('roles.destroy', $role->rol_id) }}" method="POST"
                                          class="inline-block">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                                class="bg-red-100 text-red-600 hover:bg-red-200 rounded-full p-2"
                                                onclick="return confirm('¿Estás seguro de querer eliminar este empleado?')">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                                 xmlns="http://www.w3.org/2000/svg">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                      d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Modal para crear empleado -->

        <div x-show="activeModal === 'create'" class="fixed z-10 inset-0 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            role="dialog" aria-modal="true">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                <div
                    class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                            Crear Nuevo Rol
                        </h3>
                        <div class="mt-2">
                            <form action="{{ route('roles.store') }}" method="POST" enctype="multipart/form-data">
                                @csrf

                                <div class="mb-4">
                                    <label for="rol_name"
                                           class="block text-gray-700 text-sm font-bold mb-2">Nombre del Rol:</label>
                                    <input type="text" name="rol_name" placeholder="Ingrese el nombre del Rol" id="rol_name"
                                           class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                                           required>
                                </div>
                                <div class="mb-4">
                                    <label class="block text-gray-700 text-sm font-bold mb-2">Roles:</label>
                                    <div class="flex flex-wrap -mx-2">
                                        @foreach($permissions as $permission)
                                            <div class="px-2 mb-2">
                                                <label class="inline-flex items-center">
                                                    <input type="checkbox"
                                                           name="permissions[]"
                                                           value="{{ $permission->perm_id }}"
                                                           class="absolute opacity-0 w-0 h-0"
                                                           onchange="this.nextElementSibling.classList.toggle('bg-blue-500'); this.nextElementSibling.classList.toggle('text-white');">
                                                    <span class="ml-2 text-sm font-medium py-1 px-3 rounded-full border border-gray-300 cursor-pointer transition-colors duration-200 ease-in-out hover:bg-gray-100">
                                                    {{ $permission->perm_name }}</span>
                                                </label>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                                <div class="flex items-center justify-end mt-4">
                                    <button type="button" @click="activeModal = null" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded mr-2">
                                        Cancelar
                                    </button>
                                    <button type="submit"
                                            class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                        Guardar
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection


