@extends('layouts.app')

@section('title', 'Gestión de Empleados')

@section('header', 'Gestión de Empleados')

@section('content')

    <div x-data="{ activeTab: 'employees', isModalOpen: false }" class="mb-6">
        <!-- Tabs de navegación -->
        <div class="border-b border-gray-200">
            <nav class="-mb-px flex">
                <a @click.prevent="activeTab = 'products'" :class="{'border-blue-500 text-blue-600': activeTab === 'products'}" class="cursor-pointer border-b-2 border-transparent py-4 px-6 inline-block font-medium text-sm leading-5 text-gray-500 hover:text-gray-700 hover:border-gray-300 focus:outline-none focus:text-gray-700 focus:border-gray-300" >
                    Productos
                </a>
                <a @click.prevent="activeTab = 'employees'" :class="{'border-blue-500 text-blue-600': activeTab === 'employees'}" class="cursor-pointer border-b-2 border-transparent py-4 px-6 inline-block font-medium text-sm leading-5 text-gray-500 hover:text-gray-700 hover:border-gray-300 focus:outline-none focus:text-gray-700 focus:border-gray-300">
                    Empleados
                </a>
                <a href="{{ route('categories.index') }}" class="cursor-pointer border-b-2 border-transparent py-4 px-6 inline-block font-medium text-sm leading-5 text-gray-500 hover:text-gray-700 hover:border-gray-300 focus:outline-none focus:text-gray-700 focus:border-gray-300">
                    Categorías
                </a>
            </nav>
        </div>
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

        <!-- Contenido de cada sección -->
        <div x-show="activeTab === 'employees'" class="mt-6">
            <h2 class="text-2xl font-semibold text-gray-900">Empleados</h2>
            <div class="mb-4 mt-4">
                <button @click="isModalOpen = true" class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded">
                    Crear Nuevo Empleado
                </button>
            </div>
            <div class="mb-4 mt-4 flex items-center space-x-4">
                <!-- Formulario de búsqueda -->
                <form action="{{ route('employees.index') }}" method="GET" class="flex space-x-2">
                    <!-- Campo para código de empleado -->
                    <div>
                        <label for="code_emplo" class="block text-sm font-medium text-gray-700">Código:</label>
                        <input type="text" name="code_emplo" id="code_emplo" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" placeholder="Filtrar por código" value="{{ request()->get('code_emplo') }}">
                    </div>

                    <!-- Campo para nombre de empleado -->
                    <div>
                        <label for="name_emplo" class="block text-sm font-medium text-gray-700">Nombre:</label>
                        <input type="text" name="name_emplo" id="name_emplo" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" placeholder="Filtrar por nombre" value="{{ request()->get('name_emplo') }}">
                    </div>

                    <!-- Botón para realizar la búsqueda -->
                    <div>
                        <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 mt-4 rounded focus:outline-none focus:shadow-outline">
                            Buscar
                        </button>
                    </div>
                </form>
            </div>

            <!-- Tabla de empleados -->
            <div class="overflow-x-auto bg-white rounded-lg shadow overflow-y-auto relative">
                <table class="border-collapse table-auto w-full whitespace-no-wrap bg-white table-striped relative">
                    <thead>
                    <tr class="text-left">
                        <th class="bg-blue-100 sticky top-0 border-b border-gray-200 px-6 py-3 text-blue-600 font-bold tracking-wider uppercase text-xs">ID</th>
                        <th class="bg-blue-100 sticky top-0 border-b border-gray-200 px-6 py-3 text-blue-600 font-bold tracking-wider uppercase text-xs">Código</th>
                        <th class="bg-blue-100 sticky top-0 border-b border-gray-200 px-6 py-3 text-blue-600 font-bold tracking-wider uppercase text-xs">Nombre</th>
                        <th class="bg-blue-100 sticky top-0 border-b border-gray-200 px-6 py-3 text-blue-600 font-bold tracking-wider uppercase text-xs">Apellido</th>
                        <th class="bg-blue-100 sticky top-0 border-b border-gray-200 px-6 py-3 text-blue-600 font-bold tracking-wider uppercase text-xs">DNI</th>
                        <th class="bg-blue-100 sticky top-0 border-b border-gray-200 px-6 py-3 text-blue-600 font-bold tracking-wider uppercase text-xs">Email</th>
                        <th class="bg-blue-100 sticky top-0 border-b border-gray-200 px-6 py-3 text-blue-600 font-bold tracking-wider uppercase text-xs">Estado</th>
                        <th class="bg-blue-100 sticky top-0 border-b border-gray-200 px-6 py-3 text-blue-600 font-bold tracking-wider uppercase text-xs">Acciones</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($employees as $employee)
                        <tr>
                            <td class="border-dashed border-t border-gray-200 px-6 py-4">{{ $employee->id_emplo }}
                            <td class="border-dashed border-t border-gray-200 px-6 py-4">{{ $employee->code_emplo }}</td>
                            <td class="border-dashed border-t border-gray-200 px-6 py-4">{{ $employee->name_emplo }}</td>
                            <td class="border-dashed border-t border-gray-200 px-6 py-4">{{ $employee->last_name_emplo }}</td>
                            <td class="border-dashed border-t border-gray-200 px-6 py-4">{{ $employee->dni_emplo }}</td>
                            <td class="border-dashed border-t border-gray-200 px-6 py-4">{{ $employee->email_emplo }}</td>
                            <td class="border-dashed border-t border-gray-200 px-6 py-4">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $employee->status_emplo == 'V' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ $employee->status_emplo == 'V' ? 'Vigente' : 'Retirado' }}
                                </span>
                            </td>
                            <td class="border-dashed border-t border-gray-200 px-6 py-4">
                                <a href="{{ route('employees.edit', $employee->id_emplo) }}" class="text-blue-600 hover:text-blue-900 mr-2">Editar</a>
                                <form action="{{ route('employees.destroy', $employee->id_emplo) }}" method="POST" class="inline-block">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-900" onclick="return confirm('¿Estás seguro de querer eliminar este empleado?')">Eliminar</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Modal para crear empleado -->

        <div x-show="isModalOpen === true" class="fixed z-10 inset-0 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                            Crear Nuevo Empleado
                        </h3>
                        <div class="mt-2">
                            <form action="{{ route('employees.store') }}" method="POST" enctype="multipart/form-data">
                                @csrf

                                <div class="mb-4">
                                    <label for="dni_emplo" class="block text-gray-700 text-sm font-bold mb-2">DNI:</label>
                                    <input type="text" name="dni_emplo" placeholder="Ingrese su cédula" id="us_dni" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                                    <span id="dniValidationMessage" class="text-sm mt-1"></span>
                                </div>
                                <div class="mb-4">
                                    <label for="name_emplo" class="block text-gray-700 text-sm font-bold mb-2">Nombre:</label>
                                    <input type="text" name="name_emplo" placeholder="Ingrese su nombre" id="us_name" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                                </div>
                                <div class="mb-4">
                                    <label for="last_name_emplo" class="block text-gray-700 text-sm font-bold mb-2">Apellido:</label>
                                    <input type="text" name="last_name_emplo" placeholder="Ingrese su apellido" id="us_lastName" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                                </div>
                                <div class="mb-4">
                                    <label for="email_emplo" class="block text-gray-700 text-sm font-bold mb-2">Email:</label>
                                    <input type="email" name="email_emplo" placeholder="ejemplo@correo.com"  id="email_emplo" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                                    <span id="emailValidationMessage" class="text-sm mt-1"></span>
                                </div>
                                <div class="mb-4">
                                    <label for="birthdate_emplo" class="block text-gray-700 text-sm font-bold mb-2">Fecha de Nacimiento:</label>
                                    <input type="date" name="birthdate_emplo" id="birthdate_emplo" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                                </div>
                                <div class="mb-4">
                                    <label for="id_provincia" class="block text-gray-700 text-sm font-bold mb-2">Provincia:</label>
                                    <select name="id_provincia" id="id_provincia" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                                        <option value="">Seleccione una provincia</option>
                                        @foreach($provincias as $province)
                                            <option value="{{ $province->id_provincia }}">{{ $province->nombre_provincia }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div x-data="{ imageUrl: null }" class="col-span-full">
                                    <label for="us_image" class="block text-sm font-medium text-gray-700">Foto de perfil</label>
                                    <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-blue-200 border-dashed rounded-md">
                                        <div class="space-y-1 text-center">
                                            <img x-show="imageUrl" :src="imageUrl" class="mx-auto h-32 w-32 object-cover rounded-full" x-cloak alt="imagen">
                                            <svg x-show="!imageUrl" class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48" aria-hidden="true">
                                                <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                            </svg>
                                            <div class="flex text-sm text-gray-600">
                                                <label for="us_image" class="relative cursor-pointer bg-white rounded-md font-medium text-blue-600 hover:text-blue-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-blue-500">
                                                    <span>Subir un archivo</span>
                                                    <input id="us_image" name="image_emplo" type="file" class="sr-only" @change="imageUrl = URL.createObjectURL($event.target.files[0])">
                                                </label>
                                                <p class="pl-1">o arrastrar y soltar</p>
                                            </div>
                                            <p class="text-xs text-gray-500">PNG, JPG, GIF hasta 10MB</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="mb-4">
                                    <label for="status_emplo" class="block text-gray-700 text-sm font-bold mb-2">Estado:</label>
                                    <select name="status_emplo" id="status_emplo" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                                        <option value="V">Vigente</option>
                                        <option value="R">Retirado</option>
                                    </select>
                                </div>
                                <div class="flex items-center justify-end mt-4">
                                    <button type="button" @click="isModalOpen = false" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded mr-2">
                                        Cancelar
                                    </button>
                                    <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
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



