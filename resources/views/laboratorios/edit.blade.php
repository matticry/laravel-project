@extends('layouts.app')

@section('title', 'Editar Laboratorio')

@section('header', 'Editar Laboratorio')

@section('content')
    <div class="container mx-auto px-4 py-8">
        <div class="max-w-md mx-auto bg-white rounded-lg overflow-hidden md:max-w-lg">
            <div class="md:flex">
                <div class="w-full px-6 py-8">
                    <h2 class="text-2xl font-bold text-gray-800 mb-6">Editar Laboratorio</h2>
                    <form action="{{ route('laboratorios.update', $laboratorio->id_laboratorio) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="mb-4">
                            <label for="nombre" class="block text-gray-700 text-sm font-bold mb-2">Nombre:</label>
                            <input type="text" name="nombre" id="nombre" value="{{ old('nombre', $laboratorio->nombre) }}"
                                   class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                                   required maxlength="150">
                        </div>

                        <div class="mb-4">
                            <label for="categoria" class="block text-gray-700 text-sm font-bold mb-2">Categoría:</label>
                            <input type="text" name="categoria" id="categoria" value="{{ old('categoria', $laboratorio->categoria) }}"
                                   class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                                   maxlength="100">
                        </div>

                        <div class="mb-4">
                            <label for="fecha_creacion" class="block text-gray-700 text-sm font-bold mb-2">Fecha de Creación:</label>
                            <input type="date" name="fecha_creacion" id="fecha_creacion"
                                   value="{{ old('fecha_creacion', $laboratorio->fecha_creacion ? \Carbon\Carbon::parse($laboratorio->fecha_creacion)->format('Y-m-d') : '') }}"
                                   class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                        </div>

                        <div class="mb-4">
                            <label for="id_encargado" class="block text-gray-700 text-sm font-bold mb-2">Encargado (Oftalmólogo):</label>
                            <select name="id_encargado" id="id_encargado"
                                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                                <option value="">-- Sin encargado --</option>
                                @foreach($oftalmologos as $oft)
                                    <option value="{{ $oft->us_id }}" {{ (string)old('id_encargado', $laboratorio->id_encargado) === (string)$oft->us_id ? 'selected' : '' }}>
                                        {{ $oft->us_name }} {{ $oft->us_lastName }}
                                    </option>
                                @endforeach
                            </select>
                            @if($oftalmologos->isEmpty())
                                <p class="text-xs text-red-500 mt-1">No hay usuarios con rol Oftalmólogo registrados.</p>
                            @endif
                        </div>

                        <div class="flex items-center justify-between mt-8">
                            <a href="{{ route('laboratorios.index') }}"
                               class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                                Cancelar
                            </a>
                            <button type="submit"
                                    class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                                Actualizar Laboratorio
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
