@extends('layouts.app')

@section('title', 'Gestión de Servicios')

@section('header', 'Gestión de Servicios')

@section('content')
    <div x-data="{ activeTab: 'services', isModalOpen: false, tasks: [] }" class="mb-6">
        <!-- Tabs de navegación -->
        <div class="border-b border-gray-200">
            <nav class="-mb-px flex">
                <a href="{{ route('products.index') }}" class="cursor-pointer border-b-2 border-transparent py-4 px-6 inline-block font-medium text-sm leading-5 text-gray-500 hover:text-gray-700 hover:border-gray-300 focus:outline-none focus:text-gray-700 focus:border-gray-300">
                    Productos
                </a>
                <a href="{{ route('employees.index') }}" class="cursor-pointer border-b-2 border-transparent py-4 px-6 inline-block font-medium text-sm leading-5 text-gray-500 hover:text-gray-700 hover:border-gray-300 focus:outline-none focus:text-gray-700 focus:border-gray-300">
                    Empleados
                </a>
                <a href="{{ route('categories.index') }}" class="cursor-pointer border-b-2 border-transparent py-4 px-6 inline-block font-medium text-sm leading-5 text-gray-500 hover:text-gray-700 hover:border-gray-300 focus:outline-none focus:text-gray-700 focus:border-gray-300">
                    Categorías
                </a>
                <a @click.prevent="activeTab = 'services'" :class="{'border-blue-500 text-blue-600': activeTab === 'services'}" class="cursor-pointer border-b-2 border-transparent py-4 px-6 inline-block font-medium text-sm leading-5 text-gray-500 hover:text-gray-700 hover:border-gray-300 focus:outline-none focus:text-gray-700 focus:border-gray-300" >
                    Servicios
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

        <!-- Contenido de la sección de servicios -->
        <div x-show="activeTab === 'services'" class="mt-6">
            <h2 class="text-2xl font-semibold text-gray-900">Servicios</h2>
            <div class="mb-4 mt-4">
                <button @click="isModalOpen = true; tasks = []" class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded">
                    Crear Nuevo Servicio
                </button>
            </div>
            <div class="mb-4 mt-4 flex items-center space-x-4">
                <!-- Formulario de búsqueda -->
                <form action="{{ route('services.index') }}" method="GET" class="flex space-x-2">
                    <div>
                        <label for="name_serv" class="block text-sm font-medium text-gray-700">Nombre del Servicio:</label>
                        <input type="text" name="name_serv" id="name_serv" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" placeholder="Filtrar por nombre" value="{{ request()->get('name_serv') }}">
                    </div>
                    <div>
                        <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 mt-4 rounded focus:outline-none focus:shadow-outline">
                            Buscar
                        </button>
                    </div>
                </form>
            </div>

            <!-- Tabla de servicios -->
            <!-- Tabla de servicios -->
            <div class="overflow-x-auto bg-white rounded-lg shadow overflow-y-auto relative">
                <table class="border-collapse table-auto w-full whitespace-no-wrap bg-white table-striped relative">
                    <thead>
                    <tr class="text-left">
                        <th class="bg-blue-100 sticky top-0 border-b border-gray-200 px-6 py-3 text-blue-600 font-bold tracking-wider uppercase text-xs">ID</th>
                        <th class="bg-blue-100 sticky top-0 border-b border-gray-200 px-6 py-3 text-blue-600 font-bold tracking-wider uppercase text-xs">Nombre</th>
                        <th class="bg-blue-100 sticky top-0 border-b border-gray-200 px-6 py-3 text-blue-600 font-bold tracking-wider uppercase text-xs">Descripción</th>
                        <th class="bg-blue-100 sticky top-0 border-b border-gray-200 px-6 py-3 text-blue-600 font-bold tracking-wider uppercase text-xs">Precio</th>
                        <th class="bg-blue-100 sticky top-0 border-b border-gray-200 px-6 py-3 text-blue-600 font-bold tracking-wider uppercase text-xs">Estado</th>
                        <th class="bg-blue-100 sticky top-0 border-b border-gray-200 px-6 py-3 text-blue-600 font-bold tracking-wider uppercase text-xs">Tareas</th>
                        <th class="bg-blue-100 sticky top-0 border-b border-gray-200 px-6 py-3 text-blue-600 font-bold tracking-wider uppercase text-xs">Acciones</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($services as $service)
                        <tr>
                            <td class="border-dashed border-t border-gray-200 px-6 py-4">{{ $service->id_serv }}</td>
                            <td class="border-dashed border-t border-gray-200 px-6 py-4">{{ $service->name_serv }}</td>
                            <td class="border-dashed border-t border-gray-200 px-6 py-4">{{ $service->description_serv }}</td>
                            <td class="border-dashed border-t border-gray-200 px-6 py-4">{{ $service->price_serv }}</td>
                            <td class="border-dashed border-t border-gray-200 px-6 py-4">
                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $service->status_serv == 'A' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                        {{ $service->status_serv == 'A' ? 'Activo' : 'Inactivo' }}
                    </span>
                            </td>
                            <td class="border-dashed border-t border-gray-200 px-6 py-4">
                                <div class="flex flex-wrap gap-1">
                                    @foreach($service->tasks as $task)
                                        <span class="bg-blue-100 text-blue-800 text-xs font-medium mr-1 px-2.5 py-0.5 rounded-full">
                                {{ $task->name_task }}
                            </span>
                                    @endforeach
                                </div>
                                @if($service->tasks->isEmpty())
                                    <span class="text-gray-500 text-sm">Sin tareas</span>
                                @endif
                            </td>
                            <td class="border-dashed border-t border-gray-200 px-6 py-4">
                                <a href="{{ route('services.edit', $service->id_serv) }}" class="text-blue-600 hover:text-blue-900 mr-2">Editar</a>
                                <form action="{{ route('services.destroy', $service->id_serv) }}" method="POST" class="inline-block">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-900" onclick="return confirm('¿Estás seguro de querer eliminar este servicio?')">Eliminar</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Modal para crear servicio -->
        <div x-show="isModalOpen" class="fixed z-10 inset-0 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                            Crear Nuevo Servicio
                        </h3>
                        <div class="mt-2">
                            <form action="{{ route('services.store') }}" method="POST">
                                @csrf
                                <div class="mb-4">
                                    <label for="name_serv" class="block text-gray-700 text-sm font-bold mb-2">Nombre del Servicio:</label>
                                    <input type="text" name="name_serv" id="name_serv" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                                </div>
                                <div class="mb-4">
                                    <label for="description_serv" class="block text-gray-700 text-sm font-bold mb-2">Descripción:</label>
                                    <textarea name="description_serv" id="description_serv" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required></textarea>
                                </div>
                                <div class="mb-4">
                                    <label for="price_serv" class="block text-gray-700 text-sm font-bold mb-2">Precio:</label>
                                    <input type="number" step="0.01" name="price_serv" id="price_serv" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                                </div>
                                <div class="mb-4">
                                    <label for="status_serv" class="block text-gray-700 text-sm font-bold mb-2">Estado:</label>
                                    <select name="status_serv" id="status_serv" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                                        <option value="A">Activo</option>
                                        <option value="I">Inactivo</option>
                                    </select>
                                </div>

                                <!-- Sección de tareas -->
                                <div class="mb-4">
                                    <label class="block text-gray-700 text-sm font-bold mb-2">Tareas:</label>
                                    <template x-for="(task, index) in tasks" :key="index">
                                        <div class="flex items-center mb-2">
                                            <input x-model="task.name" type="text" :name="'tasks['+index+'][name_task]'" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline mr-2" placeholder="Nombre de la tarea" required>
                                            <button type="button" @click="tasks.splice(index, 1)" class="bg-red-500 hover:bg-red-700 text-white font-bold py-1 px-2 rounded">
                                                Eliminar
                                            </button>
                                        </div>
                                    </template>
                                    <button type="button" @click="tasks.push({name: ''})" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                                        Añadir Tarea
                                    </button>
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
