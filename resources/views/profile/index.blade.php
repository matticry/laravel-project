@extends('layouts.app')

@section('title', 'Gestión de Usuarios')
@section('header', 'Gestión de Usuarios')

@section('content')

    <div x-data="{
    activeTab: 'profile',
    activeModal: null,
    userData: {},
    pacienteSeleccionado: {},
    utilizaLentes: false
}" class="mb-6">

        {{-- Tabs de navegación --}}
        <div class="border-b border-gray-200">
            <nav class="-mb-px flex">
                <a @click.prevent="activeTab = 'profile'"
                   :class="{'border-blue-500 text-blue-600': activeTab === 'profile'}"
                   class="cursor-pointer border-b-2 border-transparent py-4 px-6 inline-block font-medium text-sm leading-5 text-gray-500 hover:text-gray-700 hover:border-gray-300 focus:outline-none focus:text-gray-700 focus:border-gray-300">
                    Usuarios
                </a>
                <a href="{{ route('roles.index') }}"
                   class="cursor-pointer border-b-2 border-transparent py-4 px-6 inline-block font-medium text-sm leading-5 text-gray-500 hover:text-gray-700 hover:border-gray-300 focus:outline-none focus:text-gray-700 focus:border-gray-300">
                    Roles y Permisos
                </a>
            </nav>
        </div>

        {{-- Alerta de éxito --}}
        @if (session('success'))
            <div x-data="{ show: true }" x-show="show"
                 class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded-md relative mb-4" role="alert">
                <button @click="show = false"
                        class="absolute top-2 right-2 text-green-700 hover:bg-green-200 p-1 rounded transition duration-300">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
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
                    <button @click="show = false"
                            class="text-green-700 hover:bg-green-200 px-2 py-1 rounded transition duration-300">
                        Cerrar
                    </button>
                </div>
            </div>
        @endif

        {{-- Alerta de errores --}}
        @if ($errors->any())
            <x-bladewind::alert type="error">
                <strong class="font-bold">¡Oops!</strong>
                <span class="block sm:inline">{{ $errors->first() }}</span>
            </x-bladewind::alert>
        @endif

        {{-- Contenido de usuarios --}}
        <div x-show="activeTab === 'profile'" class="mt-6">
            <h2 class="text-2xl font-semibold text-gray-900">Usuarios</h2>

            @can('profile.store')
                <div class="mb-4 mt-4">
                    <button @click="activeModal = 'create'"
                            class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded">
                        Crear Nuevo Usuario
                    </button>
                </div>
            @endcan

            {{-- Búsqueda --}}
            <div class="mb-4 mt-4 flex items-center space-x-4">
                <form action="{{ route('profile.index') }}" method="GET" class="flex space-x-2">
                    <div>
                        <label for="name_emplo" class="block text-sm font-medium text-gray-700">Nombre:</label>
                        <input type="text" name="name_emplo" id="name_emplo"
                               class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                               placeholder="Filtrar por nombre" value="{{ request()->get('name_emplo') }}">
                    </div>
                    <div>
                        <button type="submit"
                                class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 mt-4 rounded focus:outline-none focus:shadow-outline">
                            Buscar
                        </button>
                    </div>
                </form>
            </div>

            {{-- Tabla de usuarios --}}
            <div class="overflow-x-auto bg-white rounded-lg shadow overflow-y-auto relative">
                <table class="border-collapse table-auto w-full whitespace-no-wrap bg-white table-striped relative">
                    <thead>
                    <tr class="text-left">
                        <th class="bg-blue-100 sticky top-0 border-b border-gray-200 px-6 py-3 text-blue-600 font-bold tracking-wider uppercase text-xs">ID</th>
                        <th class="bg-blue-100 sticky top-0 border-b border-gray-200 px-6 py-3 text-blue-600 font-bold tracking-wider uppercase text-xs">Nombre</th>
                        <th class="bg-blue-100 sticky top-0 border-b border-gray-200 px-6 py-3 text-blue-600 font-bold tracking-wider uppercase text-xs">Apellido</th>
                        <th class="bg-blue-100 sticky top-0 border-b border-gray-200 px-6 py-3 text-blue-600 font-bold tracking-wider uppercase text-xs">Cedula</th>
                        <th class="bg-blue-100 sticky top-0 border-b border-gray-200 px-6 py-3 text-blue-600 font-bold tracking-wider uppercase text-xs">Email</th>
                        <th class="bg-blue-100 sticky top-0 border-b border-gray-200 px-6 py-3 text-blue-600 font-bold tracking-wider uppercase text-xs">Roles</th>
                        <th class="bg-blue-100 sticky top-0 border-b border-gray-200 px-6 py-3 text-blue-600 font-bold tracking-wider uppercase text-xs">Estado</th>
                        <th class="bg-blue-100 sticky top-0 border-b border-gray-200 px-6 py-3 text-blue-600 font-bold tracking-wider uppercase text-xs">Acciones</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($users as $user)
                        <tr>
                            <td class="border-dashed border-t border-gray-200 px-6 py-4">
                                @if($user->us_id)
                                    {{ $user->us_id }}
                                @else
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">No actualizado</span>
                                @endif
                            </td>
                            <td class="border-dashed border-t border-gray-200 px-6 py-4">
                                {{ $user->us_name ?? 'No actualizado' }}
                            </td>
                            <td class="border-dashed border-t border-gray-200 px-6 py-4">
                                @if($user->us_lastName)
                                    {{ $user->us_lastName }}
                                @else
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">No actualizado</span>
                                @endif
                            </td>
                            <td class="border-dashed border-t border-gray-200 px-6 py-4">
                                @if($user->us_dni)
                                    {{ $user->us_dni }}
                                @else
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">No actualizado</span>
                                @endif
                            </td>
                            <td class="border-dashed border-t border-gray-200 px-6 py-4">
                                {{ $user->us_email ?? 'No actualizado' }}
                            </td>
                            <td class="border-dashed border-t border-gray-200 px-6 py-4">
                                @if($user->roles->isNotEmpty())
                                    {{ $user->roles->pluck('rol_name')->implode(', ') }}
                                @else
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">No actualizado</span>
                                @endif
                            </td>
                            <td class="border-dashed border-t border-gray-200 px-6 py-4">
                                @if($user->us_status)
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $user->us_status == 'A' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ $user->us_status == 'A' ? 'Activo' : 'Inactivo' }}
                                </span>
                                @else
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">No actualizado</span>
                                @endif
                            </td>
                            <td class="border-dashed border-t border-gray-200 px-6 py-4">
                                <div class="flex space-x-2">

                                    {{-- Ver usuario --}}
                                    <a @click.prevent="activeModal = 'view'; userData = {{ $user->toJson() }};"
                                       class="bg-blue-100 text-blue-600 hover:bg-blue-200 rounded-full p-2 cursor-pointer"
                                       title="Ver usuario">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                  d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                  d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                        </svg>
                                    </a>

                                    {{-- Nueva consulta --}}
                                    @can('profile.update')
                                        <a @click.prevent="
                                            activeModal = 'consulta';
                                            utilizaLentes = false;
                                            pacienteSeleccionado = {
                                                us_id: {{ $user->us_id }},
                                                us_name: '{{ addslashes($user->us_name) }}',
                                                us_lastName: '{{ addslashes($user->us_lastName) }}',
                                                us_dni: '{{ $user->us_dni }}'
                                            };"
                                           class="bg-purple-100 text-purple-600 hover:bg-purple-200 rounded-full p-2 cursor-pointer"
                                           title="Nueva consulta">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                      d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                            </svg>
                                        </a>
                                    @endcan

                                    {{-- Editar usuario --}}
                                    @can('profile.update')
                                        <a href="{{ route('profile.edit', $user->us_id) }}"
                                           class="bg-green-100 text-green-600 hover:bg-green-200 rounded-full p-2"
                                           title="Editar usuario">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                      d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                            </svg>
                                        </a>
                                    @endcan

                                    {{-- Eliminar usuario --}}
                                    @can('profile.destroy')
                                        <button type="button"
                                                class="inline-block bg-red-100 text-red-600 hover:bg-red-200 rounded-full p-2"
                                                onclick="showModal('delete-modal-{{ $user->us_id }}')"
                                                title="Eliminar usuario">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                      d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                            </svg>
                                        </button>

                                        <form id="delete-form-{{ $user->us_id }}"
                                              action="{{ route('profile.destroy', $user->us_id) }}"
                                              method="POST" class="hidden">
                                            @csrf
                                            @method('DELETE')
                                        </form>

                                        <x-bladewind::modal
                                            type="error"
                                            title="Confirmar eliminación"
                                            ok_button_label=""
                                            cancel_button_label=""
                                            name="delete-modal-{{ $user->us_id }}">
                                            <p>¿Estás seguro de que quieres eliminar este usuario? Esto no se puede deshacer.</p>
                                            <div class="mt-4">
                                                <button type="button"
                                                        class="bg-gray-300 text-gray-700 px-4 py-2 rounded mr-2"
                                                        onclick="hideModal('delete-modal-{{ $user->us_id }}')">
                                                    Cancelar
                                                </button>
                                                <button type="button"
                                                        class="bg-red-500 text-white px-4 py-2 rounded"
                                                        onclick="document.getElementById('delete-form-{{ $user->us_id }}').submit()">
                                                    Sí, eliminar
                                                </button>
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

        {{-- ===================== --}}
        {{-- MODAL: Ver usuario    --}}
        {{-- ===================== --}}
        <div x-show="activeModal === 'view'"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
             x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
             x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
             class="fixed z-10 inset-0 overflow-y-auto" role="dialog" aria-modal="true">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"></div>
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>
                <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <div class="sm:flex sm:items-start">
                            <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                                <h3 class="text-xl leading-6 font-bold text-gray-900 mb-4">Detalles del Usuario</h3>
                                <div class="mt-2 grid grid-cols-3 gap-4">
                                    <div class="col-span-1">
                                        <img :src="userData.us_image
                                                ? 'storage/' + userData.us_image
                                                : 'https://ui-avatars.com/api/?name=' + encodeURIComponent(userData.us_name) + '&background=random'"
                                             alt="User Image"
                                             class="w-32 h-32 object-cover rounded-full shadow-md">
                                    </div>
                                    <div class="col-span-2">
                                        <p class="text-sm text-gray-700"><span class="font-semibold">Nombre:</span> <span x-text="userData.us_name"></span></p>
                                        <p class="text-sm text-gray-700"><span class="font-semibold">Apellido:</span> <span x-text="userData.us_lastName"></span></p>
                                        <p class="text-sm text-gray-700"><span class="font-semibold">Cédula:</span> <span x-text="userData.us_dni"></span></p>
                                        <p class="text-sm text-gray-700"><span class="font-semibold">Email:</span> <span x-text="userData.us_email"></span></p>
                                        <p class="text-sm text-gray-700">
                                            <span class="font-semibold">Estado:</span>
                                            <span x-text="userData.us_status === 'A' ? 'Activo' : 'Inactivo'"
                                                  :class="userData.us_status === 'A' ? 'text-green-600' : 'text-red-600'"></span>
                                        </p>
                                    </div>
                                </div>
                                <div class="mt-4 space-y-2">
                                    <p class="text-sm text-gray-700"><span class="font-semibold">Dirección:</span> <span x-text="userData.us_address || 'No especificada'"></span></p>
                                    <p class="text-sm text-gray-700"><span class="font-semibold">Teléfono principal:</span> <span x-text="userData.us_first_phone || 'No especificado'"></span></p>
                                    <p class="text-sm text-gray-700"><span class="font-semibold">Teléfono secundario:</span> <span x-text="userData.us_second_phone || 'No especificado'"></span></p>
                                    <p class="text-sm text-gray-700">
                                        <span class="font-semibold">Email verificado:</span>
                                        <span x-text="userData.is_email_verified ? 'Sí' : 'No'"
                                              :class="userData.is_email_verified ? 'text-green-600' : 'text-red-600'"></span>
                                    </p>
                                    <p class="text-sm text-gray-700">
                                        <span class="font-semibold">Fecha de creación:</span>
                                        <span x-text="new Date(userData.created_at).toLocaleString('es-ES', { year: 'numeric', month: 'long', day: 'numeric', hour: '2-digit', minute: '2-digit' })"></span>
                                    </p>
                                    <p class="text-sm text-gray-700">
                                        <span class="font-semibold">Última actualización:</span>
                                        <span x-text="new Date(userData.updated_at).toLocaleString('es-ES', { year: 'numeric', month: 'long', day: 'numeric', hour: '2-digit', minute: '2-digit' })"></span>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <button type="button" @click="activeModal = null"
                                class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none sm:ml-3 sm:w-auto sm:text-sm transition duration-150 ease-in-out">
                            Cerrar
                        </button>
                    </div>
                </div>
            </div>
        </div>

        {{-- ======================== --}}
        {{-- MODAL: Crear usuario     --}}
        {{-- ======================== --}}
        <div x-show="activeModal === 'create'"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
             x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
             x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
             class="fixed z-10 inset-0 overflow-y-auto" role="dialog" aria-modal="true">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"></div>
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>
                <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Crear Nuevo Usuario</h3>
                        <form action="{{ route('profile.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="mb-4">
                                <label class="block text-gray-700 text-sm font-bold mb-2">Cedula:</label>
                                <input type="text" name="us_dni" placeholder="Ingrese su cédula" id="us_dni"
                                       class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                                       required>
                                <span id="dniValidationMessage" class="text-sm mt-1"></span>
                            </div>
                            <div class="mb-4">
                                <label class="block text-gray-700 text-sm font-bold mb-2">Nombre:</label>
                                <input type="text" name="us_name" placeholder="Ingrese su nombre" id="us_name"
                                       class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                                       required>
                            </div>
                            <div class="mb-4">
                                <label class="block text-gray-700 text-sm font-bold mb-2">Apellido:</label>
                                <input type="text" name="us_lastName" placeholder="Ingrese su apellido" id="us_lastName"
                                       class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                                       required>
                            </div>
                            <div class="mb-4">
                                <label class="block text-gray-700 text-sm font-bold mb-2">Email:</label>
                                <input type="email" name="us_email" placeholder="ejemplo@correo.com" id="email_emplo"
                                       class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                                       required>
                                <span id="emailValidationMessage" class="text-sm mt-1"></span>
                            </div>
                            <div class="mb-4">
                                <label class="block text-gray-700 text-sm font-bold mb-2">Contraseña:</label>
                                <input type="password" name="us_password" placeholder="Ingrese su contraseña" id="password_emplo"
                                       class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                                       required>
                            </div>
                            <div class="mb-4">
                                <label class="block text-gray-700 text-sm font-bold mb-2">Roles:</label>
                                <div class="flex flex-wrap -mx-2">
                                    @foreach($roles as $role)
                                        <div class="px-2 mb-2">
                                            <label class="inline-flex items-center">
                                                <input type="checkbox" name="roles[]" value="{{ $role->rol_id }}"
                                                       class="absolute opacity-0 w-0 h-0"
                                                       onchange="this.nextElementSibling.classList.toggle('bg-blue-500'); this.nextElementSibling.classList.toggle('text-white');">
                                                <span class="ml-2 text-sm font-medium py-1 px-3 rounded-full border border-gray-300 cursor-pointer transition-colors duration-200 ease-in-out hover:bg-gray-100">
                                                {{ $role->rol_name }}
                                            </span>
                                            </label>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                            <div class="flex items-center justify-end mt-4">
                                <button type="button" @click="activeModal = null"
                                        class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded mr-2">
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

        {{-- ======================== --}}
        {{-- MODAL: Nueva consulta    --}}
        {{-- ======================== --}}
        <div x-show="activeModal === 'consulta'"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
             x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
             x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
             class="fixed z-10 inset-0 overflow-y-auto" role="dialog" aria-modal="true">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"></div>
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>

                <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-2xl sm:w-full">
                    <div class="bg-white px-6 pt-5 pb-4">

                        {{-- Encabezado --}}
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg leading-6 font-bold text-gray-900">Nueva Consulta</h3>
                            <button type="button"
                                    @click="activeModal = null; utilizaLentes = false"
                                    class="text-gray-400 hover:text-gray-600 transition duration-150">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                            </button>
                        </div>

                        {{-- Banner paciente preseleccionado --}}
                        <div class="bg-blue-50 border border-blue-200 rounded-lg px-4 py-3 mb-5 flex items-center space-x-3">
                            <svg class="w-5 h-5 text-blue-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                            <div>
                                <p class="text-sm font-semibold text-blue-700">
                                    Paciente:
                                    <span x-text="pacienteSeleccionado.us_name + ' ' + pacienteSeleccionado.us_lastName"></span>
                                </p>
                                <p class="text-xs text-blue-500">
                                    Cédula: <span x-text="pacienteSeleccionado.us_dni"></span>
                                </p>
                            </div>
                        </div>

                        <form action="{{ route('consultas.store') }}" method="POST">
                            @csrf

                            {{-- FIX: x-model sincroniza el id correctamente --}}
                            <input type="hidden" name="id_paciente" x-model="pacienteSeleccionado.us_id">

                            {{-- Datos de la consulta --}}
                            <div class="mb-5">
                                <h4 class="text-xs font-bold text-blue-600 uppercase tracking-wider mb-3 border-b border-blue-100 pb-1">
                                    Datos de la Consulta
                                </h4>
                                <div>
                                    <label class="block text-gray-700 text-sm font-bold mb-2">Motivo de la consulta:</label>
                                    <input type="text" name="motivo"
                                           class="shadow border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                                           placeholder="Motivo breve de la consulta" maxlength="250" required>
                                </div>
                            </div>

                            {{-- Antecedentes del historial --}}
                            <div class="mb-5">
                                <h4 class="text-xs font-bold text-blue-600 uppercase tracking-wider mb-3 border-b border-blue-100 pb-1">
                                    Historial Clínico — Antecedentes
                                </h4>

                                <div class="mb-3">
                                    <label class="block text-gray-700 text-sm font-bold mb-2">Motivo de consulta (historial):</label>
                                    <input type="text" name="motivo_consulta"
                                           class="shadow border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                                           placeholder="Descripción detallada para el historial" maxlength="250" required>
                                </div>

                                <div class="grid grid-cols-2 gap-3 mb-3">
                                    <div>
                                        <label class="block text-gray-700 text-sm font-bold mb-2">Ant. patológicos familiares:</label>
                                        <textarea name="antecedentes_patologicos_familiares" rows="2"
                                                  class="shadow border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                                                  placeholder="Ej: Diabetes, Hipertensión..."></textarea>
                                    </div>
                                    <div>
                                        <label class="block text-gray-700 text-sm font-bold mb-2">Ant. patológicos personales:</label>
                                        <textarea name="antecedentes_patologicos_personales" rows="2"
                                                  class="shadow border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                                                  placeholder="Ej: Diabetes, Hipertensión..."></textarea>
                                    </div>
                                    <div>
                                        <label class="block text-gray-700 text-sm font-bold mb-2">Ant. oculares familiares:</label>
                                        <textarea name="antecedentes_oculares_familiares" rows="2"
                                                  class="shadow border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                                                  placeholder="Ej: Glaucoma, Cataratas..."></textarea>
                                    </div>
                                    <div>
                                        <label class="block text-gray-700 text-sm font-bold mb-2">Ant. oculares personales:</label>
                                        <textarea name="antecedentes_oculares_personales" rows="2"
                                                  class="shadow border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                                                  placeholder="Ej: Miopía, Astigmatismo..."></textarea>
                                    </div>
                                </div>

                                {{-- Uso de lentes --}}
                                <div class="flex items-center space-x-3 mb-3">
                                    <input type="hidden" name="utiliza_lentes" value="0">
                                    <input type="checkbox" name="utiliza_lentes" id="utiliza_lentes_modal" value="1"
                                           x-model="utilizaLentes"
                                           class="w-4 h-4 text-blue-600 border-gray-300 rounded">
                                    <label for="utiliza_lentes_modal" class="text-gray-700 text-sm font-bold">
                                        ¿Utiliza lentes?
                                    </label>
                                </div>

                                <div x-show="utilizaLentes" x-cloak class="grid grid-cols-2 gap-3 mb-3">
                                    <div>
                                        <label class="block text-gray-700 text-sm font-bold mb-2">Tipo de lente:</label>
                                        <input type="text" name="tipo_lente"
                                               class="shadow border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                                               placeholder="Ej: Monofocal, Bifocal..." maxlength="100">
                                    </div>
                                    <div>
                                        <label class="block text-gray-700 text-sm font-bold mb-2">Fecha inicio uso de lentes:</label>
                                        <input type="date" name="fecha_inicio_uso_lentes"
                                               class="shadow border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                                    </div>
                                </div>

                                <div>
                                    <label class="block text-gray-700 text-sm font-bold mb-2">Observaciones:</label>
                                    <textarea name="observaciones" rows="2"
                                              class="shadow border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                                              placeholder="Observaciones generales..."></textarea>
                                </div>
                            </div>

                            {{-- Botones --}}
                            <div class="flex items-center justify-end space-x-2 pt-3 border-t border-gray-100">
                                <button type="button"
                                        @click="activeModal = null; utilizaLentes = false"
                                        class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                                    Cancelar
                                </button>
                                <button type="submit"
                                        class="bg-purple-500 hover:bg-purple-700 text-white font-bold py-2 px-4 rounded">
                                    Guardar Consulta
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <script src="{{ asset('/assets/js/email_employee_validator.js') }}" defer></script>
    <script src="{{ asset('/assets/js/cedula-validator.js') }}" defer></script>

@endsection
