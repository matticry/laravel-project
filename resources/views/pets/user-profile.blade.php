@extends('layouts.app')

@section('title', 'Mascotas de ' . $user->us_name)

@section('header', 'Mascotas de ' . $user->us_name . ' ' . $user->us_lastName)

@section('content')

    <div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8" x-data="{ activeTab: 'all' }">
        <!-- Header del Usuario -->
        <div class="bg-gradient-to-r from-blue-500 to-purple-600 rounded-xl p-8 text-white mb-8">
            <div class="flex items-center space-x-6">
                <div class="h-24 w-24 rounded-full bg-white bg-opacity-20 flex items-center justify-center text-3xl font-bold">
                    {{ strtoupper(substr($user->us_name, 0, 1)) }}{{ strtoupper(substr($user->us_lastName, 0, 1)) }}
                </div>
                <div class="flex-1">
                    <h1 class="text-3xl font-bold">{{ $user->us_name }} {{ $user->us_lastName }}</h1>
                    <p class="text-blue-100 text-lg">{{ $user->us_email }}</p>
                    <p class="text-blue-200 text-sm">DNI: {{ $user->us_dni }}</p>
                </div>
                <div class="text-right">
                    <div class="text-4xl font-bold">{{ $userStats['total_pets'] }}</div>
                    <div class="text-blue-100">Mascotas Registradas</div>
                </div>
            </div>
        </div>

        <!-- Estadísticas -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <div class="bg-white rounded-xl p-6 shadow-lg border border-gray-100">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600">Total de Mascotas</p>
                        <p class="text-3xl font-bold text-blue-600">{{ $userStats['total_pets'] }}</p>
                    </div>
                    <div class="h-12 w-12 bg-blue-100 rounded-lg flex items-center justify-center">
                        <svg class="h-6 w-6 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl p-6 shadow-lg border border-gray-100">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600">Mascotas Activas</p>
                        <p class="text-3xl font-bold text-green-600">{{ $userStats['active_pets'] }}</p>
                    </div>
                    <div class="h-12 w-12 bg-green-100 rounded-lg flex items-center justify-center">
                        <svg class="h-6 w-6 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl p-6 shadow-lg border border-gray-100">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600">Especies Diferentes</p>
                        <p class="text-3xl font-bold text-purple-600">{{ $userStats['species_breakdown']->count() }}</p>
                    </div>
                    <div class="h-12 w-12 bg-purple-100 rounded-lg flex items-center justify-center">
                        <svg class="h-6 w-6 text-purple-600" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M3 4a1 1 0 011-1h12a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1V4zM3 10a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H4a1 1 0 01-1-1v-6zM14 9a1 1 0 00-1 1v6a1 1 0 001 1h2a1 1 0 001-1v-6a1 1 0 00-1-1h-2z"/>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl p-6 shadow-lg border border-gray-100">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600">Mascota más Antigua</p>
                        <p class="text-lg font-bold text-orange-600">
                            {{ $userStats['oldest_pet'] ? $userStats['oldest_pet']->name_pet : 'N/A' }}
                        </p>
                    </div>
                    <div class="h-12 w-12 bg-orange-100 rounded-lg flex items-center justify-center">
                        <svg class="h-6 w-6 text-orange-600" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tabs de filtro -->
        <div class="bg-white rounded-xl shadow-lg border border-gray-100 mb-8">
            <div class="border-b border-gray-200">
                <nav class="-mb-px flex space-x-8 px-6">
                    <button @click="activeTab = 'all'"
                            :class="{'border-blue-500 text-blue-600': activeTab === 'all', 'border-transparent text-gray-500': activeTab !== 'all'}"
                            class="py-4 px-1 border-b-2 font-medium text-sm whitespace-nowrap">
                        Todas las Mascotas ({{ $userStats['total_pets'] }})
                    </button>
                    <button @click="activeTab = 'active'"
                            :class="{'border-green-500 text-green-600': activeTab === 'active', 'border-transparent text-gray-500': activeTab !== 'active'}"
                            class="py-4 px-1 border-b-2 font-medium text-sm whitespace-nowrap">
                        Activas ({{ $userStats['active_pets'] }})
                    </button>
                    @if($userStats['inactive_pets'] > 0)
                        <button @click="activeTab = 'inactive'"
                                :class="{'border-red-500 text-red-600': activeTab === 'inactive', 'border-transparent text-gray-500': activeTab !== 'inactive'}"
                                class="py-4 px-1 border-b-2 font-medium text-sm whitespace-nowrap">
                            Inactivas ({{ $userStats['inactive_pets'] }})
                        </button>
                    @endif
                </nav>
            </div>
        </div>

        <!-- Cards de Mascotas -->
        @if($pets->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                @foreach($pets as $pet)
                    <div x-show="activeTab === 'all' ||
                           (activeTab === 'active' && '{{ $pet->status_pet }}' === 'A') ||
                           (activeTab === 'inactive' && '{{ $pet->status_pet }}' === 'I')"
                         class="bg-white rounded-xl shadow-lg border border-gray-100 overflow-hidden hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1">

                        <!-- Imagen de la mascota -->
                        <div class="h-48 bg-gradient-to-br from-blue-100 to-purple-100 relative">
                            @if($pet->has_image)
                                <img src="{{ $pet->image_url }}"
                                     alt="{{ $pet->name_pet }}"
                                     class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full flex items-center justify-center">
                                    <svg class="h-20 w-20 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                    </svg>
                                </div>
                            @endif

                            <!-- Badge de estado -->
                            <div class="absolute top-3 right-3">
                            <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $pet->status_pet === 'A' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                {{ $pet->status_pet === 'A' ? 'Activo' : 'Inactivo' }}
                            </span>
                            </div>
                        </div>

                        <!-- Información de la mascota -->
                        <div class="p-6">
                            <h3 class="text-xl font-bold text-gray-900 mb-2">{{ $pet->name_pet }}</h3>

                            <div class="space-y-2 mb-4">
                                <div class="flex items-center text-sm text-gray-600">
                                    <svg class="h-4 w-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    <span class="font-medium">Especie:</span> {{ $pet->species_pet }}
                                </div>

                                <div class="flex items-center text-sm text-gray-600">
                                    <svg class="h-4 w-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M10 2L3 7v11a1 1 0 001 1h12a1 1 0 001-1V7l-7-5z"/>
                                    </svg>
                                    <span class="font-medium">Raza:</span> {{ $pet->breed_pet }}
                                </div>

                                <div class="flex items-center text-sm text-gray-600">
                                    <svg class="h-4 w-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
                                    </svg>
                                    <span class="font-medium">Edad:</span> {{ $pet->age_pet }} años
                                </div>

                                <div class="flex items-center text-sm text-gray-600">
                                    <svg class="h-4 w-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"/>
                                    </svg>
                                    <span class="font-medium">Registrado:</span>
                                    {{ $pet->created_at ? $pet->created_at->format('d/m/Y') : 'N/A' }}
                                </div>
                            </div>

                            <!-- Botones de acción -->
                            <div class="flex space-x-2">
                                @can('pet.update')
                                    <a href="{{ route('pets.edit', $pet->id_pet) }}"
                                       class="flex-1 bg-blue-500 hover:bg-blue-600 text-white text-center py-2 px-3 rounded-lg text-sm font-medium transition-colors">
                                        Editar
                                    </a>
                                @endcan

                                <button class="bg-gray-100 hover:bg-gray-200 text-gray-700 py-2 px-3 rounded-lg text-sm font-medium transition-colors">
                                    <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M10 6a2 2 0 110-4 2 2 0 010 4zM10 12a2 2 0 110-4 2 2 0 010 4zM10 18a2 2 0 110-4 2 2 0 010 4z"/>
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <!-- Estado vacío -->
            <div class="text-center py-16">
                <svg class="mx-auto h-24 w-24 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                </svg>
                <h3 class="mt-4 text-xl font-medium text-gray-900">Sin mascotas registradas</h3>
                <p class="mt-2 text-gray-500">{{ $user->us_name }} no tiene mascotas registradas en el sistema.</p>

                @can('pet.store')
                    <div class="mt-6">
                        <a href="{{ route('pets.index') }}"
                           class="bg-blue-500 hover:bg-blue-600 text-white px-6 py-3 rounded-lg inline-flex items-center space-x-2 transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                            </svg>
                            <span>Registrar Primera Mascota</span>
                        </a>
                    </div>
                @endcan
            </div>
        @endif

        <!-- Botones de navegación -->
        <div class="mt-8 flex justify-center space-x-4">
            <a href="{{ route('pets.search-users') }}"
               class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-3 rounded-lg inline-flex items-center space-x-2 transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                <span>Buscar Otro Usuario</span>
            </a>

            <a href="{{ route('pets.index') }}"
               class="bg-blue-500 hover:bg-blue-600 text-white px-6 py-3 rounded-lg inline-flex items-center space-x-2 transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z"/>
                </svg>
                <span>Gestión de Mascotas</span>
            </a>
        </div>
    </div>

@endsection
