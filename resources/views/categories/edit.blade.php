@extends('layouts.app')

@section('title', 'Editar Categoría')

@section('header', 'Editar Categoría')

@section('content')
    <div class="container mx-auto px-4 py-8">
        <div class="max-w-md mx-auto bg-white rounded-lg overflow-hidden md:max-w-lg">
            <div class="md:flex">
                <div class="w-full px-6 py-8">
                    <h2 class="text-2xl font-bold text-gray-800 mb-6">Editar Categoría</h2>
                    <form action="{{ route('categories.update', $category->cat_id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="mb-4">
                            <label for="cat_name" class="block text-gray-700 text-sm font-bold mb-2">Nombre:</label>
                            <input type="text" name="cat_name" id="cat_name" value="{{ $category->cat_name }}" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                        </div>

                        <div class="mb-4">
                            <label for="cat_description" class="block text-gray-700 text-sm font-bold mb-2">Descripción:</label>
                            <textarea name="cat_description" id="cat_description" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" rows="3" required>{{ $category->cat_description }}</textarea>
                        </div>

                        <div class="mb-4">
                            <label for="cat_status" class="block text-gray-700 text-sm font-bold mb-2">Estado:</label>
                            <select name="cat_status" id="cat_status" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                                <option value="1" {{ $category->cat_status ? 'selected' : '' }}>Activo</option>
                                <option value="0" {{ !$category->cat_status ? 'selected' : '' }}>Inactivo</option>
                            </select>
                        </div>

                        <div class="flex items-center justify-between mt-8">
                            <a href="{{ route('categories.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                                Cancelar
                            </a>
                            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                                Actualizar Categoría
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
