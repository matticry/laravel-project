@extends('layouts.app')

@section('title', 'Editar Producto')

@section('header', 'Editar Producto')

@section('content')
    <div class="container mx-auto px-4 py-8">
        <div class="max-w-md mx-auto bg-white rounded-lg overflow-hidden md:max-w-lg">
            <div class="md:flex">
                <div class="w-full px-6 py-8">
                    <h2 class="text-2xl font-bold text-gray-800 mb-6">Editar Producto</h2>
                    <form action="{{ route('products.update', $product->pro_id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="mb-4">
                            <label for="pro_name" class="block text-gray-700 text-sm font-bold mb-2">Nombre:</label>
                            <input type="text" name="pro_name" id="pro_name" value="{{ $product->pro_name }}" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                        </div>


                        <div class="mb-4">
                            <label for="pro_amount" class="block text-gray-700 text-sm font-bold mb-2">Cantidad:</label>
                            <input type="number" name="pro_amount" id="pro_amount" value="{{ $product->pro_amount }}" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                        </div>

                        <div class="mb-4">
                            <label for="pro_unit_price" class="block text-gray-700 text-sm font-bold mb-2">Precio Unitario:</label>
                            <input type="number" step="0.01" name="pro_unit_price" id="pro_unit_price" value="{{ $product->pro_unit_price }}" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                        </div>

                        <div class="mb-4">
                            <label for="pro_description" class="block text-gray-700 text-sm font-bold mb-2">Descripción:</label>
                            <textarea name="pro_description" id="pro_description" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" rows="3">{{ $product->pro_description }}</textarea>
                        </div>

                        <div x-data="{ imageUrl: '{{ $product->pro_image ? asset('storage/' . $product->pro_image) : null }}' }" class="mb-4">
                            <label for="pro_image" class="block text-gray-700 text-sm font-bold mb-2">Imagen del producto:</label>
                            <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-blue-200 border-dashed rounded-md">
                                <div class="space-y-1 text-center">
                                    <img x-show="imageUrl" :src="imageUrl" class="mx-auto h-32 w-32 object-cover rounded-full" x-cloak alt="imagen del producto">
                                    <svg x-show="!imageUrl" class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48" aria-hidden="true">
                                        <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                    <div class="flex text-sm text-gray-600">
                                        <label for="pro_image" class="relative cursor-pointer bg-white rounded-md font-medium text-blue-600 hover:text-blue-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-blue-500">
                                            <span>Subir un archivo</span>
                                            <input id="pro_image" name="pro_image" type="file" class="sr-only" @change="imageUrl = URL.createObjectURL($event.target.files[0])">
                                        </label>
                                        <p class="pl-1">o arrastrar y soltar</p>
                                    </div>
                                    <p class="text-xs text-gray-500">PNG, JPG, GIF hasta 10MB</p>
                                </div>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="cat_id" class="block text-gray-700 text-sm font-bold mb-2">Categoría:</label>
                            <select name="cat_id" id="cat_id" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                                @foreach($categories as $category)
                                    <option value="{{ $category->cat_id }}" {{ $product->cat_id == $category->cat_id ? 'selected' : '' }}>
                                        {{ $category->cat_name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-4">
                            <label for="pro_status" class="block text-gray-700 text-sm font-bold mb-2">Estado:</label>
                            <select name="pro_status" id="pro_status" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                                <option value="A" {{ $product->pro_status == 'A' ? 'selected' : '' }}>Activo</option>
                                <option value="I" {{ $product->pro_status == 'I' ? 'selected' : '' }}>Inactivo</option>
                            </select>
                        </div>

                        <div class="flex items-center justify-between mt-8">
                            <a href="{{ route('products.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                                Cancelar
                            </a>
                            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                                Actualizar Producto
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
