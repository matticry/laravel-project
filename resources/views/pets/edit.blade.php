@extends('layouts.app')

@section('title', 'Editar Mascota')

@section('header', 'Editar Mascota')

@section('content')

    <div class="max-w-4xl mx-auto py-6 sm:px-6 lg:px-8" x-data="editPetForm()">
        <div class="bg-white shadow rounded-lg">
            <div class="px-4 py-5 sm:p-6">
                <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">
                    Editar Información de {{ $pet->name_pet }}
                </h3>

                @if ($errors->any())
                    <x-bladewind::alert type="error">
                        <strong class="font-bold">¡Oops!</strong>
                        <span class="block sm:inline">{{ $errors->first() }}</span>
                    </x-bladewind::alert>
                @endif

                <form action="{{ route('pets.update', $pet->id_pet) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                    @csrf
                    @method('PUT')

                    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                        <div>
                            <label for="name_pet" class="block text-sm font-medium text-gray-700">
                                Nombre de la Mascota
                            </label>
                            <input type="text"
                                   name="name_pet"
                                   id="name_pet"
                                   value="{{ old('name_pet', $pet->name_pet) }}"
                                   class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                                   required>
                        </div>

                        <div>
                            <label for="age_pet" class="block text-sm font-medium text-gray-700">
                                Edad (años)
                            </label>
                            <input type="number"
                                   name="age_pet"
                                   id="age_pet"
                                   min="0"
                                   max="30"
                                   value="{{ old('age_pet', $pet->age_pet) }}"
                                   class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                                   required>
                        </div>

                        <div>
                            <label for="species_pet" class="block text-sm font-medium text-gray-700">
                                Especie
                            </label>
                            <select name="species_pet"
                                    id="species_pet"
                                    x-model="species"
                                    @change="clearBreedImage()"
                                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                                    required>
                                <option value="">Seleccione la especie</option>
                                <option value="Perro" {{ old('species_pet', $pet->species_pet) == 'Perro' ? 'selected' : '' }}>Perro</option>
                                <option value="Gato" {{ old('species_pet', $pet->species_pet) == 'Gato' ? 'selected' : '' }}>Gato</option>
                                <option value="Ave" {{ old('species_pet', $pet->species_pet) == 'Ave' ? 'selected' : '' }}>Ave</option>
                                <option value="Conejo" {{ old('species_pet', $pet->species_pet) == 'Conejo' ? 'selected' : '' }}>Conejo</option>
                                <option value="Hámster" {{ old('species_pet', $pet->species_pet) == 'Hámster' ? 'selected' : '' }}>Hámster</option>
                                <option value="Otro" {{ old('species_pet', $pet->species_pet) == 'Otro' ? 'selected' : '' }}>Otro</option>
                            </select>
                        </div>

                        <div>
                            <label for="breed_pet" class="block text-sm font-medium text-gray-700">
                                Raza
                            </label>
                            <div class="flex mt-1">
                                <input type="text"
                                       name="breed_pet"
                                       id="breed_pet"
                                       x-model="breed"
                                       value="{{ old('breed_pet', $pet->breed_pet) }}"
                                       class="block w-full border-gray-300 rounded-l-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                                       required>
                                <button type="button"
                                        @click="searchBreedImage()"
                                        :disabled="!species || !breed || species === 'Otro'"
                                        class="bg-blue-500 hover:bg-blue-600 disabled:bg-gray-400 text-white px-4 py-2 rounded-r-md">
                                    <svg x-show="!searching" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                    </svg>
                                    <svg x-show="searching" class="w-5 h-5 animate-spin" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                    </svg>
                                </button>
                            </div>
                        </div>

                        <div>
                            <label for="status_pet" class="block text-sm font-medium text-gray-700">
                                Estado
                            </label>
                            <select name="status_pet"
                                    id="status_pet"
                                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                                    required>
                                <option value="A" {{ old('status_pet', $pet->status_pet) == 'A' ? 'selected' : '' }}>Activo</option>
                                <option value="I" {{ old('status_pet', $pet->status_pet) == 'I' ? 'selected' : '' }}>Inactivo</option>
                            </select>
                        </div>

                        <div>
                            <label for="id_usu" class="block text-sm font-medium text-gray-700">
                                Propietario
                            </label>
                            <select name="id_usu"
                                    id="id_usu"
                                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                                    required>
                                @foreach($users as $user)
                                    <option value="{{ $user->us_id }}" {{ old('id_usu', $pet->id_usu) == $user->us_id ? 'selected' : '' }}>
                                        {{ $user->us_name }} {{ $user->us_lastName }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <!-- Sección de imagen -->
                    <div class="space-y-4">
                        <label class="block text-sm font-medium text-gray-700">Imagen de la Mascota</label>

                        <!-- Imagen actual -->
                        @if($pet->has_image)
                            <div class="border rounded-lg p-4 bg-gray-50">
                                <p class="text-sm text-gray-600 mb-2">Imagen actual:</p>
                                <div class="flex items-center space-x-4">
                                    <img src="{{ $pet->image_url }}"
                                         alt="{{ $pet->name_pet }}"
                                         class="h-24 w-24 object-cover rounded-lg">
                                    <div>
                                    <span class="text-sm text-gray-500">
                                        {{ $pet->is_uploaded_image ? 'Imagen subida por el usuario' : 'Imagen de la API' }}
                                    </span>
                                        @if($pet->is_uploaded_image)
                                            <p class="text-xs text-gray-400">{{ $pet->image_pet }}</p>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endif

                        <!-- Imagen de la API (si se encontró nueva) -->
                        <div x-show="apiImageUrl" class="border-2 border-dashed border-blue-300 rounded-lg p-4">
                            <div class="text-center">
                                <img :src="apiImageUrl" alt="Imagen de la raza" class="mx-auto h-32 w-32 object-cover rounded-lg">
                                <p class="mt-2 text-sm text-gray-600">Nueva imagen de la raza encontrada</p>
                                <div class="mt-2">
                                    <label class="inline-flex items-center">
                                        <input type="checkbox"
                                               name="use_api_image"
                                               x-model="useApiImage"
                                               class="form-checkbox">
                                        <span class="ml-2 text-sm">Usar esta nueva imagen</span>
                                    </label>
                                    <input type="hidden" name="api_image_url" :value="apiImageUrl">
                                </div>
                            </div>
                        </div>

                        <!-- Subida de nueva imagen -->
                        <div x-data="{ imagePreview: null }" class="border-2 border-dashed border-gray-300 rounded-lg p-4">
                            <div class="text-center">
                                <img x-show="imagePreview" :src="imagePreview" class="mx-auto h-32 w-32 object-cover rounded-lg mb-4" alt="Vista previa">
                                <svg x-show="!imagePreview" class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                    <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                                <div class="flex text-sm text-gray-600">
                                    <label for="pet_image" class="relative cursor-pointer bg-white rounded-md font-medium text-blue-600 hover:text-blue-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-blue-500">
                                        <span>Cambiar imagen</span>
                                        <input id="pet_image"
                                               name="pet_image"
                                               type="file"
                                               accept="image/*"
                                               class="sr-only"
                                               @change="imagePreview = URL.createObjectURL($event.target.files[0]); useApiImage = false">
                                    </label>
                                    <p class="pl-1">o arrastrar y soltar</p>
                                </div>
                                <p class="text-xs text-gray-500">PNG, JPG, GIF hasta 2MB</p>
                            </div>
                        </div>
                    </div>

                    <div class="flex items-center justify-end space-x-3 pt-6">
                        <a href="{{ route('pets.index') }}"
                           class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded">
                            Cancelar
                        </a>
                        <button type="submit"
                                class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                            Actualizar Mascota
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function editPetForm() {
            return {
                species: '{{ old("species_pet", $pet->species_pet) }}',
                breed: '{{ old("breed_pet", $pet->breed_pet) }}',
                apiImageUrl: '',
                useApiImage: false,
                searching: false,

                clearBreedImage() {
                    this.apiImageUrl = '';
                    this.useApiImage = false;
                },

                async searchBreedImage() {
                    if (!this.species || !this.breed || this.species === 'Otro') {
                        return;
                    }

                    this.searching = true;

                    try {
                        const response = await fetch('{{ route("pets.search-breed-image") }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                            },
                            body: JSON.stringify({
                                species: this.species,
                                breed: this.breed
                            })
                        });

                        const data = await response.json();

                        if (data.success && data.image_url) {
                            this.apiImageUrl = data.image_url;
                            this.useApiImage = false;
                        } else {
                            alert('No se encontró imagen para esta raza. Puedes subir una imagen propia.');
                        }
                    } catch (error) {
                        console.error('Error:', error);
                        alert('Error al buscar imagen. Inténtalo de nuevo.');
                    }

                    this.searching = false;
                }
            }
        }
    </script>

@endsection
