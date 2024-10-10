@extends('layouts.app')

@section('title', 'Editar Servicio')

@section('header', 'Editar Servicio')

@section('content')
    <div class="container mx-auto px-4 py-6">
        <h1 class="text-2xl font-semibold text-gray-900 mb-6">Editar Servicio</h1>

        @if ($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                <strong class="font-bold">¡Oops!</strong>
                <span class="block sm:inline">Por favor corrige los siguientes errores:</span>
                <ul class="list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('services.update', $service->id_serv) }}" method="POST" x-data="{ tasks: {{ json_encode($service->tasks) }} }">
            @csrf
            @method('PUT')

            <div class="mb-4">
                <label for="name_serv" class="block text-gray-700 text-sm font-bold mb-2">Nombre del Servicio:</label>
                <input type="text" name="name_serv" id="name_serv" value="{{ old('name_serv', $service->name_serv) }}" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
            </div>

            <div class="mb-4">
                <label for="description_serv" class="block text-gray-700 text-sm font-bold mb-2">Descripción:</label>
                <textarea name="description_serv" id="description_serv" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>{{ old('description_serv', $service->description_serv) }}</textarea>
            </div>

            <div class="mb-4">
                <label for="price_serv" class="block text-gray-700 text-sm font-bold mb-2">Precio:</label>
                <input type="number" step="0.01" name="price_serv" id="price_serv" value="{{ old('price_serv', $service->price_serv) }}" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
            </div>

            <div class="mb-4">
                <label for="status_serv" class="block text-gray-700 text-sm font-bold mb-2">Estado:</label>
                <select name="status_serv" id="status_serv" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                    <option value="A" {{ old('status_serv', $service->status_serv) == 'A' ? 'selected' : '' }}>Activo</option>
                    <option value="I" {{ old('status_serv', $service->status_serv) == 'I' ? 'selected' : '' }}>Inactivo</option>
                </select>
            </div>

            <!-- Sección de tareas -->
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2">Tareas:</label>
                <template x-for="(task, index) in tasks" :key="index">
                    <div class="flex items-center mb-2">
                        <input x-model="task.name_task" type="text" :name="'tasks['+index+'][name_task]'" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline mr-2" placeholder="Nombre de la tarea" required>
                        <input type="hidden" :name="'tasks['+index+'][id_task]'" :value="task.id_task">
                        <button type="button" @click="tasks.splice(index, 1)" class="bg-red-500 hover:bg-red-700 text-white font-bold py-1 px-2 rounded">
                            Eliminar
                        </button>
                    </div>
                </template>
                <button type="button" @click="tasks.push({name_task: '', id_task: null})" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded mt-2">
                    Añadir Tarea
                </button>
            </div>

            <div class="flex items-center justify-between mt-6">
                <a href="{{ route('services.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                    Cancelar
                </a>
                <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                    Actualizar Servicio
                </button>
            </div>
        </form>
    </div>

@endsection
