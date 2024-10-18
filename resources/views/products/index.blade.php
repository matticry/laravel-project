@extends('layouts.app')

@section('title', 'Gestión de Productos')

@section('header', 'Gestión de Productos')

@section('content')

        <div x-data="{ activeTab: 'products', isModalOpen: false }" class="mb-6">
        <!-- Tabs de navegación -->
        <div class="border-b border-gray-200">
            <nav class="-mb-px flex">
                <a @click.prevent="activeTab = 'products'" :class="{'border-blue-500 text-blue-600': activeTab === 'products'}" class="cursor-pointer border-b-2 border-transparent py-4 px-6 inline-block font-medium text-sm leading-5 text-gray-500 hover:text-gray-700 hover:border-gray-300 focus:outline-none focus:text-gray-700 focus:border-gray-300" >
                    Productos
                </a>
                @can('view.index.employee')
                    <a href="{{ route('employees.index') }}" class="cursor-pointer border-b-2 border-transparent py-4 px-6 inline-block font-medium text-sm leading-5 text-gray-500 hover:text-gray-700 hover:border-gray-300 focus:outline-none focus:text-gray-700 focus:border-gray-300">
                        Empleados
                    </a>
                @endcan
                @can('view.index.category')
                    <a href="{{ route('categories.index') }}" class="cursor-pointer border-b-2 border-transparent py-4 px-6 inline-block font-medium text-sm leading-5 text-gray-500 hover:text-gray-700 hover:border-gray-300 focus:outline-none focus:text-gray-700 focus:border-gray-300">
                        Categorías
                    </a>
                @endcan
                @can('view.index.service')
                    <a href="{{ route('services.index') }}" class="cursor-pointer border-b-2 border-transparent py-4 px-6 inline-block font-medium text-sm leading-5 text-gray-500 hover:text-gray-700 hover:border-gray-300 focus:outline-none focus:text-gray-700 focus:border-gray-300">
                        Servicios
                    </a>
                @endcan
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

        <!-- Contenido de la sección de productos -->
        <div x-show="activeTab === 'products'" class="mt-6">
            <h2 class="text-2xl font-semibold text-gray-900">Gestión de Productos</h2>
            @can('product.store')
                <div class="mb-4 mt-4">
                    <button @click="isModalOpen = true" class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded">
                        Crear Nuevo Producto
                    </button>
                </div>
            @endcan
            <div class="mb-4 mt-4 flex items-center space-x-4">
                <!-- Formulario de búsqueda -->
                <form action="{{ route('products.index') }}" method="GET" class="flex space-x-2">
                    <div>
                        <label for="pro_name" class="block text-sm font-medium text-gray-700">Nombre del Producto:</label>
                        <input type="text" name="pro_name" id="pro_name" autocomplete="off" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" placeholder="Filtrar por nombre" value="{{ request()->get('pro_name') }}">
                    </div>
                    <div>
                        <label for="pro_code" class="block text-sm font-medium text-gray-700">Código de Producto:</label>
                        <input type="text" name="pro_code" autocomplete="off" id="pro_code" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" placeholder="Filtrar por nombre" value="{{ request()->get('pro_name') }}">
                    </div>
                    <div>
                        <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 mt-4 rounded focus:outline-none focus:shadow-outline">
                            Buscar
                        </button>
                    </div>
                </form>
            </div>

            <!-- Tabla de productos -->
            <!-- Tabla de productos -->
            <div class="overflow-x-auto bg-white rounded-lg shadow overflow-y-auto relative">
                <table class="border-collapse table-auto w-full whitespace-no-wrap bg-white table-striped relative">
                    <thead>
                    <tr class="text-left">
                        <th class="bg-blue-100 sticky top-0 border-b border-gray-200 px-6 py-3 text-blue-600 font-bold tracking-wider uppercase text-xs">Numero del Producto</th>
                        <th class="bg-blue-100 sticky top-0 border-b border-gray-200 px-6 py-3 text-blue-600 font-bold tracking-wider uppercase text-xs">Código del Producto</th>
                        <th class="bg-blue-100 sticky top-0 border-b border-gray-200 px-6 py-3 text-blue-600 font-bold tracking-wider uppercase text-xs">Imagen</th>
                        <th class="bg-blue-100 sticky top-0 border-b border-gray-200 px-6 py-3 text-blue-600 font-bold tracking-wider uppercase text-xs">Nombre del Producto</th>
                        <th class="bg-blue-100 sticky top-0 border-b border-gray-200 px-6 py-3 text-blue-600 font-bold tracking-wider uppercase text-xs">Cantidad</th>
                        <th class="bg-blue-100 sticky top-0 border-b border-gray-200 px-6 py-3 text-blue-600 font-bold tracking-wider uppercase text-xs">Precio Unitario (USD)</th>
                        <th class="bg-blue-100 sticky top-0 border-b border-gray-200 px-6 py-3 text-blue-600 font-bold tracking-wider uppercase text-xs">Estado</th>
                        <th class="bg-blue-100 sticky top-0 border-b border-gray-200 px-6 py-3 text-blue-600 font-bold tracking-wider uppercase text-xs">Categoría</th>
                        <th class="bg-blue-100 sticky top-0 border-b border-gray-200 px-6 py-3 text-blue-600 font-bold tracking-wider uppercase text-xs">Acciones</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($products as $product)
                        <tr>
                            <td class="border-dashed border-t border-gray-200 px-6 py-4">{{ $product->pro_id }}</td>
                            <td class="border-dashed border-t border-gray-200 px-6 py-4">{{ $product->pro_code }}</td>
                            <td class="border-dashed border-t border-gray-200 px-6 py-4">
                                @if($product->pro_image)
                                    <img src="{{ asset('storage/' . $product->pro_image) }}" alt="{{ $product->pro_name }}" class="h-12 w-12 object-cover rounded-full">
                                @else
                                    <div class="h-12 w-12 rounded-full bg-gray-200 flex items-center justify-center">
                                        <span class="text-gray-500 text-xs">Sin imagen</span>
                                    </div>
                                @endif
                            </td>
                            <td class="border-dashed border-t border-gray-200 px-6 py-4">{{ $product->pro_name }}</td>
                            <td class="border-dashed border-t border-gray-200 px-6 py-4">{{ $product->pro_amount }}</td>
                            <td class="border-dashed border-t border-gray-200 px-6 py-4">${{ number_format($product->pro_unit_price, 2) }}</td>
                            <td class="border-dashed border-t border-gray-200 px-6 py-4">
                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $product->pro_status == 'A' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                        {{ $product->pro_status == 'A' ? 'Activo' : 'Inactivo' }}
                    </span>
                            </td>
                            <td class="border-dashed border-t border-gray-200 px-6 py-4">{{ $product->category->cat_name ?? 'N/A' }}</td>
                            <td class="border-dashed border-t border-gray-200 px-6 py-4">
                                <div class="flex space-x-2">
                                    @can('product.update')
                                        <a href="{{ route('products.edit', $product->pro_id) }}"
                                           class="bg-green-100 text-green-600 hover:bg-green-200 rounded-full p-2">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                                 xmlns="http://www.w3.org/2000/svg">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                      d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                            </svg>
                                        </a>
                                    @endcan
                                        @can('product.destroy')
                                            <form id="delete-form-{{ $product->pro_id }}" action="{{ route('products.destroy', $product->pro_id) }}" method="POST" class="inline-block">
                                                @csrf
                                                @method('DELETE')
                                                <button type="button"
                                                        class="bg-red-100 text-red-600 hover:bg-red-200 rounded-full p-2"
                                                        onclick="showModal('delete-modal-{{ $product->pro_id }}')">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                                         xmlns="http://www.w3.org/2000/svg">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                              d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                    </svg>
                                                </button>
                                            </form>

                                            <x-bladewind::modal
                                                type="error"
                                                title="Confirmar eliminación"
                                                ok_button_label=""
                                                cancel_button_label=""
                                                name="delete-modal-{{ $product->pro_id }}">
                                                <p>¿Estás seguro de que deseas eliminar este producto? Esta acción no se puede deshacer.</p>
                                                <div class="mt-4">
                                                    <button type="button" class="bg-gray-300 text-gray-700 px-4 py-2 rounded mr-2" onclick="hideModal('delete-modal-{{ $product->pro_id }}')">Cancelar</button>
                                                    <button type="button" class="bg-red-500 text-white px-4 py-2 rounded" onclick="document.getElementById('delete-form-{{ $product->pro_id }}').submit()">Sí, eliminar</button>
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


        <!-- Modal para crear producto -->
        <div x-show="isModalOpen" class="fixed z-10 inset-0 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                            Crear Nuevo Producto
                        </h3>
                        <div class="mt-2">
                            <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="mb-4">
                                    <label for="pro_name" class="block text-gray-700 text-sm font-bold mb-2">Nombre:</label>
                                    <input type="text" name="pro_name" id="pro_name" placeholder="Ingrese el nombre del producto" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                                </div>
                                <div class="mb-4">
                                    <label for="pro_amount" class="block text-gray-700 text-sm font-bold mb-2">Cantidad:</label>
                                    <input type="number" name="pro_amount" placeholder="Ingrese la cantidad" id="pro_amount" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                                </div>
                                <div class="relative mb-4">
                                    <label for="pro_unit_price" class="block text-gray-700 text-sm font-bold mb-2">Precio Unitario:</label>
                                    <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-500 mt-7">$</span>
                                    <input type="number"  step="0.01" name="pro_unit_price" id="pro_unit_price"
                                           class="block w-full pl-7 pr-20 rounded-md border border-gray-300 py-2 text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-indigo-600 focus:border-indigo-600 sm:text-sm"
                                           placeholder="0.00" required>
                                    <span class="absolute inset-y-0 right-0 flex items-center pr-3 text-gray-500 mt-7">USD</span>
                                </div>

                                <div class="mb-4">
                                    <label for="pro_description" class="block text-gray-700 text-sm font-bold mb-2">Descripción:</label>
                                    <textarea name="pro_description" id="pro_description" placeholder="Ingrese la descripción" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"></textarea>
                                </div>
                                <div class="mb-4">
                                    <label for="cat_id" class="block text-gray-700 text-sm font-bold mb-2">Categoría:</label>
                                    <select name="cat_id" id="cat_id" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                                        <option value="">Seleccione una categoría</option>
                                        @foreach($categories as $category)
                                            <option value="{{ $category->cat_id }}">{{ $category->cat_name }}</option>
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
                                                    <input id="us_image" name="pro_image" type="file" class="sr-only" @change="imageUrl = URL.createObjectURL($event.target.files[0])">
                                                </label>
                                                <p class="pl-1">o arrastrar y soltar</p>
                                            </div>
                                            <p class="text-xs text-gray-500">PNG, JPG, GIF hasta 10MB</p>
                                        </div>
                                    </div>
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
