@extends('layouts.app')

@section('title', 'Gestión de Mascotas')

@section('header', 'Gestión de Mascotas')

@section('content')

    <div x-data="{ activeTab: 'pets', isModalOpen: false }" class="mb-6">
        <!-- Tabs de navegación -->
        <div class="border-b border-gray-200">
            <nav class="-mb-px flex">
                <a @click.prevent="activeTab = 'pets'" :class="{'border-blue-500 text-blue-600': activeTab === 'pets'}" class="cursor-pointer border-b-2 border-transparent py-4 px-6 inline-block font-medium text-sm leading-5 text-gray-500 hover:text-gray-700 hover:border-gray-300 focus:outline-none focus:text-gray-700 focus:border-gray-300">
                    Mascotas
                </a>

            </nav>
        </div>

        @if (session('success'))
            <div x-data="{ show: true }" x-show="show" class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded-md relative mb-4" role="alert">
                <button @click="show = false" class="absolute top-2 right-2 text-green-700 hover:bg-green-200 p-1 rounded transition duration-300">
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
                    <a href="{{ route('pets.statistics') }}" class="text-green-700 hover:bg-green-200 px-2 py-1 rounded transition duration-300 mr-3">Ver estadísticas</a>
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

        <div x-show="activeTab === 'pets'" class="mt-6">
            <h2 class="text-2xl font-semibold text-gray-900">Gestión de Mascotas</h2>
            @can('pet.store')
                <div class="mb-4 mt-4">
                    <button @click="isModalOpen = true" class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded">
                        Registrar Nueva Mascota
                    </button>

                    <a href="{{ route('pets.search-users') }}"
                       class="bg-green-500 hover:bg-green-600 text-white font-bold py-2 px-4 rounded mr-2">
                        👤 Consultar Usuario y sus Mascotas
                    </a>
                </div>
            @endcan



            <div class="mb-4 mt-4 flex items-center space-x-4">
                <!-- Formulario de búsqueda -->
                <form action="{{ route('pets.index') }}" method="GET" class="flex space-x-2">
                    <div>
                        <label for="name_pet" class="block text-sm font-medium text-gray-700">Nombre de la Mascota:</label>
                        <input type="text" name="name_pet" id="name_pet" autocomplete="off" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" placeholder="Filtrar por nombre" value="{{ request()->get('name_pet') }}">
                    </div>
                    <div>
                        <label for="species_pet" class="block text-sm font-medium text-gray-700">Especie:</label>
                        <input type="text" name="species_pet" autocomplete="off" id="species_pet" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" placeholder="Filtrar por especie" value="{{ request()->get('species_pet') }}">
                    </div>
                    <div>
                        <label for="id_usu" class="block text-sm font-medium text-gray-700">Propietario:</label>
                        <select name="id_usu" id="id_usu" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                            <option value="">Todos los propietarios</option>
                            @foreach($users as $user)
                                <option value="{{ $user->us_id }}" {{ request()->get('id_usu') == $user->us_id ? 'selected' : '' }}>
                                    {{ $user->us_name }} {{ $user->us_lastName }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 mt-4 rounded focus:outline-none focus:shadow-outline">
                            Buscar
                        </button>
                    </div>
                </form>
            </div>

            <!-- Tabla de mascotas -->
            <div class="overflow-x-auto bg-white rounded-lg shadow overflow-y-auto relative">
                <table class="border-collapse table-auto w-full whitespace-no-wrap bg-white table-striped relative">
                    <thead>
                    <tr class="text-left">
                        <th class="bg-blue-100 sticky top-0 border-b border-gray-200 px-6 py-3 text-blue-600 font-bold tracking-wider uppercase text-xs">ID</th>
                        <th class="bg-blue-100 sticky top-0 border-b border-gray-200 px-6 py-3 text-blue-600 font-bold tracking-wider uppercase text-xs">Foto</th>
                        <th class="bg-blue-100 sticky top-0 border-b border-gray-200 px-6 py-3 text-blue-600 font-bold tracking-wider uppercase text-xs">Nombre</th>
                        <th class="bg-blue-100 sticky top-0 border-b border-gray-200 px-6 py-3 text-blue-600 font-bold tracking-wider uppercase text-xs">Especie</th>
                        <th class="bg-blue-100 sticky top-0 border-b border-gray-200 px-6 py-3 text-blue-600 font-bold tracking-wider uppercase text-xs">Raza</th>
                        <th class="bg-blue-100 sticky top-0 border-b border-gray-200 px-6 py-3 text-blue-600 font-bold tracking-wider uppercase text-xs">Edad</th>
                        <th class="bg-blue-100 sticky top-0 border-b border-gray-200 px-6 py-3 text-blue-600 font-bold tracking-wider uppercase text-xs">Propietario</th>
                        <th class="bg-blue-100 sticky top-0 border-b border-gray-200 px-6 py-3 text-blue-600 font-bold tracking-wider uppercase text-xs">Estado</th>
                        <th class="bg-blue-100 sticky top-0 border-b border-gray-200 px-6 py-3 text-blue-600 font-bold tracking-wider uppercase text-xs">Acciones</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($pets as $pet)
                        <tr>
                            <td class="border-dashed border-t border-gray-200 px-6 py-4">{{ $pet->id_pet }}</td>
                            <td class="border-dashed border-t border-gray-200 px-6 py-4">
                                @if($pet->has_image)
                                    <img src="{{ $pet->image_url }}"
                                         alt="{{ $pet->name_pet }}"
                                         class="h-12 w-12 object-cover rounded-full"
                                         onerror="this.src='{{ asset('images/no-pet-image.png') }}'">
                                @else
                                    <div class="h-12 w-12 rounded-full bg-gray-200 flex items-center justify-center">
                                        <svg class="h-8 w-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                        </svg>
                                    </div>
                                @endif
                            </td>
                            <td class="border-dashed border-t border-gray-200 px-6 py-4 font-medium">{{ $pet->name_pet }}</td>
                            <td class="border-dashed border-t border-gray-200 px-6 py-4">{{ $pet->species_pet }}</td>
                            <td class="border-dashed border-t border-gray-200 px-6 py-4">{{ $pet->breed_pet }}</td>
                            <td class="border-dashed border-t border-gray-200 px-6 py-4">{{ $pet->age_pet }} años</td>
                            <td class="border-dashed border-t border-gray-200 px-6 py-4">
                                {{ $pet->owner->us_name ?? 'N/A' }} {{ $pet->owner->us_lastName ?? '' }}
                            </td>
                            <td class="border-dashed border-t border-gray-200 px-6 py-4">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $pet->status_pet == 'A' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                {{ $pet->status_pet == 'A' ? 'Activo' : 'Inactivo' }}
                            </span>
                            </td>
                            <td class="border-dashed border-t border-gray-200 px-6 py-4">
                                <div class="flex space-x-2">
                                    @can('pet.update')
                                        <a href="{{ route('pets.edit', $pet->id_pet) }}"
                                           class="bg-green-100 text-green-600 hover:bg-green-200 rounded-full p-2">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                                 xmlns="http://www.w3.org/2000/svg">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                      d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                            </svg>
                                        </a>
                                    @endcan
                                    @can('pet.destroy')
                                        <form id="delete-form-{{ $pet->id_pet }}" action="{{ route('pets.destroy', $pet->id_pet) }}" method="POST" class="inline-block">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button"
                                                    class="bg-red-100 text-red-600 hover:bg-red-200 rounded-full p-2"
                                                    onclick="showModal('delete-modal-{{ $pet->id_pet }}')">
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
                                            name="delete-modal-{{ $pet->id_pet }}">
                                            <p>¿Estás seguro de que deseas eliminar esta mascota? Esta acción no se puede deshacer.</p>
                                            <div class="mt-4">
                                                <button type="button" class="bg-gray-300 text-gray-700 px-4 py-2 rounded mr-2" onclick="hideModal('delete-modal-{{ $pet->id_pet }}')">Cancelar</button>
                                                <button type="button" class="bg-red-500 text-white px-4 py-2 rounded" onclick="document.getElementById('delete-form-{{ $pet->id_pet }}').submit()">Sí, eliminar</button>
                                            </div>
                                        </x-bladewind::modal>
                                    @endcan
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="border-dashed border-t border-gray-200 px-6 py-4 text-center text-gray-500">
                                No se encontraron mascotas registradas
                            </td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Paginación -->
            @if($pets->hasPages())
                <div class="mt-4">
                    {{ $pets->links() }}
                </div>
            @endif
        </div>

        <!-- Modal para crear mascota -->
        <div x-show="isModalOpen" class="fixed z-10 inset-0 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-2xl sm:w-full">
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                            Registrar Nueva Mascota
                        </h3>
                        <div class="mt-2">
                            <form action="{{ route('pets.store') }}" method="POST" enctype="multipart/form-data" x-data="petForm()">
                                @csrf
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div class="mb-4">
                                        <label for="name_pet" class="block text-gray-700 text-sm font-bold mb-2">Nombre de la Mascota:</label>
                                        <input type="text" name="name_pet" id="name_pet" placeholder="Ingrese el nombre de la mascota" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                                    </div>
                                    <div class="mb-4">
                                        <label for="age_pet" class="block text-gray-700 text-sm font-bold mb-2">Edad (años):</label>
                                        <input type="number" name="age_pet" id="age_pet" min="0" max="30" placeholder="Ingrese la edad" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                                    </div>
                                    <div class="mb-4">
                                        <label for="species_pet" class="block text-gray-700 text-sm font-bold mb-2">Especie:</label>
                                        <select name="species_pet"
                                                id="species_pet"
                                                x-model="species"
                                                @change="clearBreedImage()"
                                                class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                                                required>
                                            <option value="">Seleccione la especie</option>
                                            <option value="Perro">Perro</option>
                                            <option value="Gato">Gato</option>
                                            <option value="Ave">Ave</option>
                                            <option value="Conejo">Conejo</option>
                                            <option value="Hámster">Hámster</option>
                                            <option value="Otro">Otro</option>
                                        </select>
                                    </div>
                                    <div class="mb-4">
                                        <label for="breed_pet" class="block text-gray-700 text-sm font-bold mb-2">Raza:</label>
                                        <div class="flex">
                                            <input type="text"
                                                   name="breed_pet"
                                                   id="breed_pet"
                                                   x-model="breed"
                                                   placeholder="Ingrese la raza"
                                                   class="shadow appearance-none border rounded-l w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                                                   required>
                                            <button type="button"
                                                    @click="searchBreedImage()"
                                                    :disabled="!species || !breed || species === 'Otro'"
                                                    class="bg-blue-500 hover:bg-blue-600 disabled:bg-gray-400 text-white px-4 py-2 rounded-r">
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
                                    <div class="mb-4 md:col-span-2">
                                        <label for="id_usu" class="block text-gray-700 text-sm font-bold mb-2">Propietario:</label>
                                        <select name="id_usu" id="id_usu" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                                            <option value="">Seleccione el propietario</option>
                                            @foreach($users as $user)
                                                <option value="{{ $user->us_id }}">{{ $user->us_name }} {{ $user->us_lastName }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <!-- Sección de imagen -->
                                <div class="mb-4">
                                    <label class="block text-gray-700 text-sm font-bold mb-2">Imagen de la Mascota:</label>

                                    <!-- Imagen de la API (si se encontró) -->
                                    <div x-show="apiImageUrl" class="mb-4">
                                        <div class="border-2 border-dashed border-blue-300 rounded-lg p-4">
                                            <div class="text-center">
                                                <img :src="apiImageUrl" alt="Imagen de la raza" class="mx-auto h-32 w-32 object-cover rounded-lg">
                                                <p class="mt-2 text-sm text-gray-600">Imagen de la raza encontrada</p>
                                                <div class="mt-2">
                                                    <label class="inline-flex items-center">
                                                        <input type="checkbox"
                                                               name="use_api_image"
                                                               value="1"
                                                               x-model="useApiImage"
                                                               class="form-checkbox h-4 w-4 text-blue-600">
                                                        <span class="ml-2 text-sm">Usar esta imagen</span>
                                                    </label>
                                                    <!-- Hidden input para pasar la URL -->
                                                    <input type="hidden" name="api_image_url" :value="useApiImage ? apiImageUrl : ''">
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Subida de archivo -->
                                    <div x-data="{ imagePreview: null }" class="border-2 border-dashed border-gray-300 rounded-lg p-4">
                                        <div class="text-center">
                                            <img x-show="imagePreview" :src="imagePreview" class="mx-auto h-32 w-32 object-cover rounded-lg mb-4" alt="Vista previa">
                                            <svg x-show="!imagePreview" class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                                <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                            </svg>
                                            <div class="flex text-sm text-gray-600">
                                                <label for="pet_image" class="relative cursor-pointer bg-white rounded-md font-medium text-blue-600 hover:text-blue-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-blue-500">
                                                    <span>Subir imagen propia</span>
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

                                    <p class="text-xs text-gray-500 mt-2">
                                        💡 Tip: Selecciona una especie (Perro/Gato) y una raza, luego haz clic en buscar para encontrar una imagen automáticamente
                                    </p>
                                </div>

                                <div class="flex items-center justify-end mt-4">
                                    <button type="button" @click="isModalOpen = false" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded mr-2">
                                        Cancelar
                                    </button>
                                    <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                        Registrar Mascota
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function petForm() {
            return {
                species: '',
                breed: '',
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

                    console.log('Iniciando búsqueda:', this.species, this.breed);
                    this.searching = true;

                    try {
                        const csrfToken = document.querySelector('meta[name="csrf-token"]');
                        if (!csrfToken) {
                            throw new Error('CSRF token no encontrado');
                        }

                        const response = await fetch('/pets/search-breed-image', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'Accept': 'application/json',
                                'X-CSRF-TOKEN': csrfToken.getAttribute('content')
                            },
                            body: JSON.stringify({
                                species: this.species,
                                breed: this.breed
                            })
                        });

                        console.log('Response status:', response.status);

                        if (!response.ok) {
                            const errorText = await response.text();
                            console.error('Error response:', errorText);
                            throw new Error(`HTTP ${response.status}: ${errorText}`);
                        }

                        const data = await response.json();
                        console.log('Response data:', data);

                        if (data.success && data.image_url) {
                            this.apiImageUrl = data.image_url;
                            this.useApiImage = false;
                            console.log('Imagen encontrada:', data.image_url);
                        } else {
                            alert('No se encontró imagen para esta raza: ' + (data.message || 'Sin detalles'));
                        }
                    } catch (error) {
                        console.error('Error completo:', error);
                        alert('Error al buscar imagen: ' + error.message);
                    }

                    this.searching = false;
                }
            }
        }
    </script>

@endsection
