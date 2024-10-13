@extends('layouts.app')

@section('title', 'Gestión de Usuarios')

@section('header', 'Gestión de Usuarios')

@section('content')

    <div x-data="{ activeTab: 'profile', activeModal: null, userData: {} }" class="mb-6">
        <!-- Tabs de navegación -->
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

        <!-- Contenido de cada sección -->
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
            <div class="mb-4 mt-4 flex items-center space-x-4">
                <!-- Formulario de búsqueda -->
                <form action="{{ route('profile.index') }}" method="GET" class="flex space-x-2">
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
                            Nombre
                        </th>
                        <th class="bg-blue-100 sticky top-0 border-b border-gray-200 px-6 py-3 text-blue-600 font-bold tracking-wider uppercase text-xs">
                            Apellido
                        </th>
                        <th class="bg-blue-100 sticky top-0 border-b border-gray-200 px-6 py-3 text-blue-600 font-bold tracking-wider uppercase text-xs">
                            Cedula
                        </th>
                        <th class="bg-blue-100 sticky top-0 border-b border-gray-200 px-6 py-3 text-blue-600 font-bold tracking-wider uppercase text-xs">
                            Email
                        </th>
                        <th class="bg-blue-100 sticky top-0 border-b border-gray-200 px-6 py-3 text-blue-600 font-bold tracking-wider uppercase text-xs">
                            Roles
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
                    @foreach($users as $user)
                        <tr>
                            <td class="border-dashed border-t border-gray-200 px-6 py-4">
                                @if($user->us_id)
                                    {{ $user->us_id }}
                                @else
                                    <span
                                        class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800"> No actualizado</span>

                                @endif
                            </td>
                            <td class="border-dashed border-t border-gray-200 px-6 py-4">{{ $user->us_name ?? 'No actualizado' }}</td>
                            <td class="border-dashed border-t border-gray-200 px-6 py-4">
                                @if($user->us_lastName)
                                    {{ $user->us_lastName }}
                                @else
                                    <span
                                        class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800 italic text-gray-500 text-sm"> No actualizado</span>
                                @endif
                            </td>
                            <td class="border-dashed border-t border-gray-200 px-6 py-4">
                                @if($user->us_dni)
                                    {{ $user->us_dni }}
                                @else
                                    <span
                                        class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800 italic text-gray-500 text-sm">No actualizado</span>
                                @endif
                            </td>
                            <td class="border-dashed border-t border-gray-200 px-6 py-4">{{ $user->us_email ?? 'No actualizado' }}</td>
                            <td class="border-dashed border-t border-gray-200 px-6 py-4">
                                @if($user->roles->isNotEmpty())
                                    {{ $user->roles->pluck('rol_name')->implode(', ') }}
                                @else
                                    <span
                                        class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800 italic text-gray-500 text-sm"> No actualizado</span>
                                @endif
                            </td>
                            <td class="border-dashed border-t border-gray-200 px-6 py-4">
                                @if($user->us_status)
                                    <span
                                        class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $user->us_status == 'A' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                    {{ $user->us_status == 'A' ? 'Activo' : 'Inactivo' }}
                </span>
                                @else
                                    <span
                                        class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800"> No actualizado</span>

                                @endif
                            </td>
                            <td class="border-dashed border-t border-gray-200 px-6 py-4">
                                <div class="flex space-x-2">
                                    <a @click.prevent="activeModal = 'view'; userData = {{ $user->toJson() }};"
                                       class="bg-blue-100 text-blue-600 hover:bg-blue-200 rounded-full p-2 cursor-pointer">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                             xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                  d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                  d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                        </svg>
                                    </a>
                                    @can('profile.update')

                                        <a href="{{ route('profile.edit', $user->us_id) }}"
                                           class="bg-green-100 text-green-600 hover:bg-green-200 rounded-full p-2">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                                 xmlns="http://www.w3.org/2000/svg">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                      d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                            </svg>
                                        </a>
                                    @endcan
                                    @can('profile.destroy')
                                        <button type="button"
                                                class="inline-block bg-red-100 text-red-600 hover:bg-red-200 rounded-full p-2"
                                                onclick="showModal('delete-modal-{{ $user->us_id }}')">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                                 xmlns="http://www.w3.org/2000/svg">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                      d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                            </svg>
                                        </button>

                                        <form id="delete-form-{{ $user->us_id }}" action="{{ route('profile.destroy', $user->us_id) }}" method="POST" class="hidden">
                                            @csrf
                                            @method('DELETE')
                                        </form>

                                        <x-bladewind::modal
                                            type="error"
                                            title="Confirmar eliminación"
                                            ok_button_label=""
                                            cancel_button_label=""
                                            name="delete-modal-{{ $user->us_id }}">
                                            <p>¿Estás seguro de que quieres eliminar este servicio? Esto no se puede deshacer.</p>
                                            <div class="mt-4">
                                                <button type="button" class="bg-gray-300 text-gray-700 px-4 py-2 rounded mr-2" onclick="hideModal('delete-modal-{{ $user->us_id }}')">Cancelar</button>
                                                <button type="button" class="bg-red-500 text-white px-4 py-2 rounded" onclick="document.getElementById('delete-form-{{ $user->us_id }}').submit()">Sí, eliminar</button>
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

        <!-- Modal para ver usuario -->
        <!-- Modal para ver usuario -->
        <div x-show="activeModal === 'view'"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
             x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
             x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
             class="fixed z-10 inset-0 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <div class="sm:flex sm:items-start">
                            <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                                <h3 class="text-xl leading-6 font-bold text-gray-900 mb-4" id="modal-title">
                                    Detalles del Usuario
                                </h3>
                                <div class="mt-2 grid grid-cols-3 gap-4">
                                    <div class="col-span-1">
                                        <img :src="userData.us_image ? 'storage/' + userData.us_image : 'https://ui-avatars.com/api/?name=' + encodeURIComponent(userData.us_name) + '&background=random'"
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
                                                  :class="userData.us_status === 'A' ? 'text-green-600' : 'text-red-600'">
                                    </span>
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
                                              :class="userData.is_email_verified ? 'text-green-600' : 'text-red-600'">
                                </span>
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
                        <button type="button" @click="activeModal = null" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:ml-3 sm:w-auto sm:text-sm transition duration-150 ease-in-out">
                            Cerrar
                        </button>
                    </div>
                </div>
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
                            Crear Nuevo Usuario
                        </h3>
                        <div class="mt-2">

                            <form action="{{ route('profile.store') }}" method="POST" enctype="multipart/form-data">
                                @csrf

                                <div class="mb-4">
                                    <label for="dni_emplo"
                                           class="block text-gray-700 text-sm font-bold mb-2">Cedula:</label>
                                    <input type="text" name="us_dni" placeholder="Ingrese su cédula" id="us_dni"
                                           class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                                           required>
                                    <span id="dniValidationMessage" class="text-sm mt-1"></span>
                                </div>
                                <div class="mb-4">
                                    <label for="name_emplo"
                                           class="block text-gray-700 text-sm font-bold mb-2">Nombre:</label>
                                    <input type="text" name="us_name" placeholder="Ingrese su nombre" id="us_name"
                                           class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                                           required>
                                </div>
                                <div class="mb-4">
                                    <label for="last_name_emplo" class="block text-gray-700 text-sm font-bold mb-2">Apellido:</label>
                                    <input type="text" name="us_lastName" placeholder="Ingrese su apellido"
                                           id="us_lastName"
                                           class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                                           required>
                                </div>
                                <div class="mb-4">
                                    <label for="email_emplo"
                                           class="block text-gray-700 text-sm font-bold mb-2">Email:</label>
                                    <input type="email" name="us_email" placeholder="ejemplo@correo.com"
                                           id="email_emplo"
                                           class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                                           required>
                                    <span id="emailValidationMessage" class="text-sm mt-1"></span>
                                </div>
                                <div class="mb-4">
                                    <label for="password_emplo" class="block text-gray-700 text-sm font-bold mb-2">Contraseña:</label>
                                    <input type="password" name="us_password" placeholder="Ingrese su contraseña"
                                           id="password_emplo"
                                           class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                                           required>
                                </div>
                                <div class="mb-4">
                                    <label class="block text-gray-700 text-sm font-bold mb-2">Roles:</label>
                                    <div class="flex flex-wrap -mx-2">
                                        @foreach($roles as $role)
                                            <div class="px-2 mb-2">
                                                <label class="inline-flex items-center">
                                                    <input type="checkbox"
                                                           name="roles[]"
                                                           value="{{ $role->rol_id }}"
                                                           class="absolute opacity-0 w-0 h-0"
                                                           onchange="this.nextElementSibling.classList.toggle('bg-blue-500'); this.nextElementSibling.classList.toggle('text-white');">
                                                    <span class="ml-2 text-sm font-medium py-1 px-3 rounded-full border border-gray-300 cursor-pointer transition-colors duration-200 ease-in-out hover:bg-gray-100">
                                                    {{ $role->rol_name }}</span>
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
    <script src="{{ asset('/assets/js/email_employee_validator.js') }}" defer></script>
    <script src="{{ asset('/assets/js/cedula-validator.js') }}" defer></script>

@endsection



