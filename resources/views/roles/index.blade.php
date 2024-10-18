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

        <!-- Contenido de cada sección -->
        <div x-show="activeTab === 'roles'" class="mt-6">
            <h2 class="text-2xl font-semibold text-gray-900">Roles y Permisos</h2>
            <div class="mb-4 mt-4">
                @can('button.role.add')
                    <button @click="activeModal = 'create'"
                            class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded">
                        Crear Nuevo Rol
                    </button>
                @endcan
            </div>
            <div class="mb-4 mt-4 flex items-center space-x-4">
                <!-- Formulario de búsqueda -->
                <form action="{{ route('roles.index') }}" method="GET" class="flex space-x-2">

                    <div>
                        <label for="rol_name" class="block text-sm font-medium text-gray-700">Nombre del Rol:</label>
                        <input type="text" name="rol_name" id="rol_name"
                               class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                               placeholder="Filtrar por nombre" value="{{ request()->get('rol_name') }}">
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
                                    @can('button.role.edit')
                                    <a href="{{ route('roles.edit', $role->rol_id) }}"
                                       class="bg-green-100 text-green-600 hover:bg-green-200 rounded-full p-2">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                             xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                  d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                        </svg>
                                    </a>
                                    @endcan
                                        @can('button.role.destroy')
                                            <button type="button"
                                                    class="inline-block bg-red-100 text-red-600 hover:bg-red-200 rounded-full p-2"
                                                    onclick="showModal('delete-modal-{{ $role->rol_id }}')">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                                     xmlns="http://www.w3.org/2000/svg">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                          d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                </svg>
                                            </button>

                                            <form id="delete-form-{{ $role->rol_id }}" action="{{ route('roles.destroy', $role->rol_id) }}" method="POST" class="hidden">
                                                @csrf
                                                @method('DELETE')
                                            </form>

                                            <x-bladewind::modal
                                                type="error"
                                                title="Confirmar eliminación"
                                                ok_button_label=""
                                                cancel_button_label=""
                                                name="delete-modal-{{ $role->rol_id }}">
                                                <p>¿Estás seguro de que quieres eliminar este servicio? Esto no se puede deshacer.</p>
                                                <div class="mt-4">
                                                    <button type="button" class="bg-gray-300 text-gray-700 px-4 py-2 rounded mr-2" onclick="hideModal('delete-modal-{{ $role->rol_id }}')">Cancelar</button>
                                                    <button type="button" class="bg-red-500 text-white px-4 py-2 rounded" onclick="document.getElementById('delete-form-{{ $role->rol_id }}').submit()">Sí, eliminar</button>
                                                </div>
                                            </x-bladewind::modal>
                                        @endcan
                                </div>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Modal para crear empleado -->

        <div x-show="activeModal === 'create'" class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 transition-opacity bg-gray-500 bg-opacity-75" aria-hidden="true"></div>
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                <div class="inline-block overflow-hidden text-left align-bottom transition-all transform bg-white rounded-lg shadow-xl sm:my-8 sm:align-middle sm:max-w-6xl sm:w-full">
                    <div class="px-4 pt-5 pb-4 bg-white sm:p-6 sm:pb-4">
                        <h2 class="mb-4 text-2xl font-bold text-gray-900" id="modal-title">
                            Crear Nuevo Rol
                        </h2>
                        <form action="{{ route('roles.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="mb-6">
                                <label for="rol_name" class="block mb-2 text-sm font-medium text-gray-700">Nombre del Rol</label>
                                <input type="text" name="rol_name" id="rol_name" class="w-full px-3 py-2 text-gray-700 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Ingrese el nombre del Rol" required>
                            </div>

                            <div class="mb-6">
                                <div class="flex items-center justify-between mb-4">
                                    <h3 class="text-xl font-semibold text-gray-900">Permisos</h3>
                                    <label class="flex items-center cursor-pointer">
                                        <span class="mr-2 text-sm font-medium text-gray-700">Seleccionar todos</span>
                                        <div class="relative">
                                            <input type="checkbox" id="toggle-all" class="sr-only" @change="toggleAllPermissions">
                                            <div class="block w-14 h-8 bg-gray-300 rounded-full"></div>
                                            <div class="absolute w-6 h-6 transition-transform duration-200 ease-in-out transform bg-white rounded-full dot left-1 top-1"></div>
                                        </div>
                                    </label>
                                </div>

                                <div class="grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-3">
                                    @php
                                        $permissionGroups = [
                                            'Gestión de Roles' => 'role',
                                            'Gestión de Productos' => 'product',
                                            'Gestión de Categorías' => 'category',
                                            'Gestión de Servicios' => 'service',
                                            'Gestión de Empleados' => 'employee',
                                            'Órdenes de Trabajo' => 'workorders',
                                            'Calendario' => 'calendario',
                                            'Reportes' => 'reports',
                                            'Botones' => 'button'
                                        ];
                                    @endphp

                                    @foreach($permissionGroups as $groupName => $groupKey)
                                        <div class="p-4 bg-gray-50 rounded-lg shadow-sm">
                                            <h4 class="mb-3 text-lg font-medium text-gray-800">{{ $groupName }}</h4>
                                            <div class="space-y-2">
                                                @foreach($permissions as $permission)
                                                    @if(strpos($permission->perm_name, $groupKey) !== false)
                                                        <label class="flex items-center">
                                                            <input type="checkbox" name="permissions[]" value="{{ $permission->perm_id }}" class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                                                            <span class="ml-2 text-sm text-gray-700">{{ $permission->perm_name }}</span>
                                                        </label>
                                                    @endif
                                                @endforeach
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                            <div class="flex justify-end mt-6 space-x-3">
                                <button type="button" @click="activeModal = null" class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                    Cancelar
                                </button>
                                <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-blue-600 border border-transparent rounded-md shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                    Guardar
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
@endsection



