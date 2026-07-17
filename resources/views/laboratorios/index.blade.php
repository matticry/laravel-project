@php use Carbon\Carbon; @endphp
@extends('layouts.app')

@section('title', 'Gestión de Laboratorios')

@section('header', 'Gestión de Laboratorios')

@section('content')
    <div x-data="{ isModalOpen: false }" class="mb-6">
        @can('laboratorio.store')
            <div class="mb-4 mt-4">
                <button @click="isModalOpen = true"
                        class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded">
                    Crear Nuevo Laboratorio
                </button>
            </div>
        @endcan

        @if (session('success'))
            <div x-data="{ show: true }" x-show="show"
                 class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded-md relative mb-4"
                 role="alert">
                <div class="flex items-center">
                    <svg class="h-5 w-5 text-green-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                              d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                              clip-rule="evenodd"/>
                    </svg>
                    <strong class="font-bold">¡Éxito!</strong>
                </div>
                <span class="block mt-2">{{ session('success') }}</span>
                <div class="mt-3">
                    <button @click="show = false"
                            class="text-green-700 hover:bg-green-200 px-2 py-1 rounded transition duration-300">Cerrar
                    </button>
                </div>
            </div>
        @endif

        @if ($errors->any())
            )
            <x-bladewind::alert type="error">
                <strong class="font-bold">¡Oops!</strong>
                <span class="block sm:inline">{{ $errors->first() }}</span>
            </x-bladewind::alert>
        @endif

        {{-- Formulario de búsqueda --}}
        <form action="{{ route('laboratorios.index') }}" method="GET" class="flex flex-wrap gap-3 mb-4">
            <div>
                <label for="nombre" class="block text-sm font-medium text-gray-700">Nombre:</label>
                <input type="text" name="nombre" id="nombre"
                       class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                       placeholder="Filtrar por nombre" value="{{ request()->get('nombre') }}">
            </div>
            <div>
                <label for="categoria" class="block text-sm font-medium text-gray-700">Categoría:</label>
                <input type="text" name="categoria" id="categoria"
                       class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                       placeholder="Filtrar por categoría" value="{{ request()->get('categoria') }}">
            </div>
            <div class="self-end">
                <button type="submit"
                        class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                    Buscar
                </button>
            </div>
        </form>

        {{-- Tabla de laboratorios --}}
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
                        Categoría
                    </th>
                    <th class="bg-blue-100 sticky top-0 border-b border-gray-200 px-6 py-3 text-blue-600 font-bold tracking-wider uppercase text-xs">
                        Fecha de Creación
                    </th>
                    <th class="bg-blue-100 sticky top-0 border-b border-gray-200 px-6 py-3 text-blue-600 font-bold tracking-wider uppercase text-xs">
                        Encargado
                    </th>
                    <th class="bg-blue-100 sticky top-0 border-b border-gray-200 px-6 py-3 text-blue-600 font-bold tracking-wider uppercase text-xs">
                        Acciones
                    </th>
                </tr>
                </thead>
                <tbody>
                @forelse($laboratorios as $laboratorio)
                    <tr>
                        <td class="border-dashed border-t border-gray-200 px-6 py-4">{{ $laboratorio->id_laboratorio }}</td>
                        <td class="border-dashed border-t border-gray-200 px-6 py-4">{{ $laboratorio->nombre }}</td>
                        <td class="border-dashed border-t border-gray-200 px-6 py-4">{{ $laboratorio->categoria ?? '—' }}</td>
                        <td class="border-dashed border-t border-gray-200 px-6 py-4">
                            {{ $laboratorio->fecha_creacion ? Carbon::parse($laboratorio->fecha_creacion)->format('Y-m-d') : '—' }}
                        </td>
                        <td class="border-dashed border-t border-gray-200 px-6 py-4">
                            {{ $laboratorio->encargado ? $laboratorio->encargado->us_name . ' ' . ($laboratorio->encargado->us_lastName ?? '') : '—' }}
                        </td>
                        <td class="border-dashed border-t border-gray-200 px-6 py-4">
                            <div class="flex space-x-2">
                                @can('laboratorio.update')
                                    <a href="{{ route('laboratorios.edit', $laboratorio->id_laboratorio) }}"
                                       class="inline-block bg-green-100 text-green-600 hover:bg-green-200 rounded-full p-2">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                             xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                  d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                        </svg>
                                    </a>
                                @endcan
                                @can('laboratorio.destroy')
                                    <button type="button"
                                            class="inline-block bg-red-100 text-red-600 hover:bg-red-200 rounded-full p-2"
                                            onclick="showModal('delete-modal-{{ $laboratorio->id_laboratorio }}')">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                             xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                  d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                        </svg>
                                    </button>

                                    <form id="delete-form-{{ $laboratorio->id_laboratorio }}"
                                          action="{{ route('laboratorios.destroy', $laboratorio->id_laboratorio) }}"
                                          method="POST" class="hidden">
                                        @csrf
                                        @method('DELETE')
                                    </form>

                                    <x-bladewind::modal
                                        type="error"
                                        title="Confirmar eliminación"
                                        ok_button_label=""
                                        cancel_button_label=""
                                        name="delete-modal-{{ $laboratorio->id_laboratorio }}">
                                        <p>¿Estás seguro de que quieres eliminar este laboratorio? Esto no se puede
                                            deshacer.</p>
                                        <div class="mt-4">
                                            <button type="button"
                                                    class="bg-gray-300 text-gray-700 px-4 py-2 rounded mr-2"
                                                    onclick="hideModal('delete-modal-{{ $laboratorio->id_laboratorio }}')">
                                                Cancelar
                                            </button>
                                            <button type="button" class="bg-red-500 text-white px-4 py-2 rounded"
                                                    onclick="document.getElementById('delete-form-{{ $laboratorio->id_laboratorio }}').submit()">
                                                Sí, eliminar
                                            </button>
                                        </div>
                                    </x-bladewind::modal>
                                @endcan
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6"
                            class="border-dashed border-t border-gray-200 px-6 py-4 text-center text-gray-500">
                            No hay laboratorios registrados.
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>

        {{-- Modal para crear laboratorio --}}
        <div x-show="isModalOpen" class="fixed z-10 inset-0 overflow-y-auto" aria-labelledby="modal-title" role="dialog"
             aria-modal="true" style="display:none;">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                <div
                    class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                            Crear Nuevo Laboratorio
                        </h3>
                        <div class="mt-2">
                            <form action="{{ route('laboratorios.store') }}" method="POST">
                                @csrf
                                <div class="mb-4">
                                    <label for="nombre"
                                           class="block text-gray-700 text-sm font-bold mb-2">Nombre:</label>
                                    <input type="text" name="nombre" id="nombre"
                                           class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                                           required maxlength="150">
                                </div>
                                <div class="mb-4">
                                    <label for="categoria"
                                           class="block text-gray-700 text-sm font-bold mb-2">Categoría:</label>
                                    <input type="text" name="categoria" id="categoria"
                                           class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                                           maxlength="100">
                                </div>
                                <div class="mb-4">
                                    <label for="fecha_creacion" class="block text-gray-700 text-sm font-bold mb-2">Fecha
                                        de Creación:</label>
                                    <input type="date" name="fecha_creacion" id="fecha_creacion"
                                           class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                                </div>
                                <div class="mb-4">
                                    <label for="id_encargado" class="block text-gray-700 text-sm font-bold mb-2">Encargado
                                        (Oftalmólogo):</label>
                                    <select name="id_encargado" id="id_encargado"
                                            class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                                        <option value="">-- Sin encargado --</option>
                                        @foreach($oftalmologos as $oft)
                                            <option value="{{ $oft->us_id }}">
                                                {{ $oft->us_name }} {{ $oft->us_lastName }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @if($oftalmologos->isEmpty())
                                        <p class="text-xs text-red-500 mt-1">No hay usuarios con rol Oftalmólogo registrados.</p>
                                    @endif
                                </div>
                                <div class="flex items-center justify-end mt-4">
                                    <button type="button" @click="isModalOpen = false"
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
        </div>
    </div>
@endsection

<script>
    function hideModal(modalName) {
        Bladewind.closeModal(modalName);
    }
</script>
