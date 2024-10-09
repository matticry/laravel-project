@extends('layouts.app')

@section('title', 'Gestión de Productos y Servicios')

@section('header', 'Gestión de Productos y Servicios')

@section('content')


    <div x-data="{ activeTab: 'categories', isModalOpen: false }" class="mb-6">
        <!-- Tabs de navegación -->
        <div class="border-b border-gray-200">
            <nav class="-mb-px flex">
                <a href="{{ route('products.index') }}" class="cursor-pointer border-b-2 border-transparent py-4 px-6 inline-block font-medium text-sm leading-5 text-gray-500 hover:text-gray-700 hover:border-gray-300 focus:outline-none focus:text-gray-700 focus:border-gray-300">
                    Productos
                </a>
                <a href="{{ route('employees.index') }}" class="cursor-pointer border-b-2 border-transparent py-4 px-6 inline-block font-medium text-sm leading-5 text-gray-500 hover:text-gray-700 hover:border-gray-300 focus:outline-none focus:text-gray-700 focus:border-gray-300" >
                    Empleados
                </a>
                <a @click.prevent="activeTab = 'categories'" :class="{'border-blue-500 text-blue-600': activeTab === 'categories'}" class="cursor-pointer border-b-2 border-transparent py-4 px-6 inline-block font-medium text-sm leading-5 text-gray-500 hover:text-gray-700 hover:border-gray-300 focus:outline-none focus:text-gray-700 focus:border-gray-300">
                    Categorías
                </a>
                <a href="{{ route('services.index') }}" class="cursor-pointer border-b-2 border-transparent py-4 px-6 inline-block font-medium text-sm leading-5 text-gray-500 hover:text-gray-700 hover:border-gray-300 focus:outline-none focus:text-gray-700 focus:border-gray-300">
                    Servicios
                </a>
            </nav>
        </div>

        <div x-show="activeTab === 'categories'" class="mt-6">
            <h2 class="text-2xl font-semibold text-gray-900">Categorías</h2>
            <div class="mb-4 mt-4">
                <button @click="isModalOpen = true" class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded">
                    Crear Nueva Categoría
                </button>
            </div>

            <!-- Tabla de categorías -->
            <div class="overflow-x-auto bg-white rounded-lg shadow overflow-y-auto relative">
                <table class="border-collapse table-auto w-full whitespace-no-wrap bg-white table-striped relative">
                    <thead>
                    <tr class="text-left">
                        <th class="bg-blue-100 sticky top-0 border-b border-gray-200 px-6 py-3 text-blue-600 font-bold tracking-wider uppercase text-xs">ID</th>
                        <th class="bg-blue-100 sticky top-0 border-b border-gray-200 px-6 py-3 text-blue-600 font-bold tracking-wider uppercase text-xs">Nombre</th>
                        <th class="bg-blue-100 sticky top-0 border-b border-gray-200 px-6 py-3 text-blue-600 font-bold tracking-wider uppercase text-xs">Descripción</th>
                        <th class="bg-blue-100 sticky top-0 border-b border-gray-200 px-6 py-3 text-blue-600 font-bold tracking-wider uppercase text-xs">Estado</th>
                        <th class="bg-blue-100 sticky top-0 border-b border-gray-200 px-6 py-3 text-blue-600 font-bold tracking-wider uppercase text-xs">Acciones</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($categories as $category)
                        <tr>
                            <td class="border-dashed border-t border-gray-200 px-6 py-4">{{ $category->cat_id }}</td>
                            <td class="border-dashed border-t border-gray-200 px-6 py-4">{{ $category->cat_name }}</td>
                            <td class="border-dashed border-t border-gray-200 px-6 py-4">{{ $category->cat_description }}</td>
                            <td class="border-dashed border-t border-gray-200 px-6 py-4">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $category->cat_status ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ $category->cat_status ? 'Activo' : 'Inactivo' }}
                                </span>
                            </td>
                            <td class="border-dashed border-t border-gray-200 px-6 py-4">
                                <a href="{{ route('categories.edit', $category->cat_id) }}" class="text-blue-600 hover:text-blue-900 mr-2">Editar</a>
                                <form action="{{ route('categories.destroy', $category->cat_id) }}" method="POST" class="inline-block">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-900" onclick="return confirm('¿Estás seguro de querer eliminar esta categoría?')">Eliminar</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Modal para crear categoría -->
        <div x-show="isModalOpen" class="fixed z-10 inset-0 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                            Crear Nueva Categoría
                        </h3>
                        <div class="mt-2">
                            <form action="{{ route('categories.store') }}" method="POST">
                                @csrf
                                <div class="mb-4">
                                    <label for="cat_name" class="block text-gray-700 text-sm font-bold mb-2">Nombre:</label>
                                    <input type="text" name="cat_name" id="cat_name" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                                </div>
                                <div class="mb-4">
                                    <label for="cat_description" class="block text-gray-700 text-sm font-bold mb-2">Descripción:</label>
                                    <textarea name="cat_description" id="cat_description" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" rows="3" required></textarea>
                                </div>
                                <div class="mb-4">
                                    <label for="cat_status" class="block text-gray-700 text-sm font-bold mb-2">Estado:</label>
                                    <select name="cat_status" id="cat_status" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                                        <option value="1">Activo</option>
                                        <option value="0">Inactivo</option>
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
@endsection
